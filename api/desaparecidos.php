<?php
require_once '../includes/db.php';
header('Content-Type: application/json; charset=utf-8');

// Função para forçar UTF-8 em todos os campos
function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
    }
    return $mixed;
}

$filtro = filter_input(INPUT_GET, 'filtro', FILTER_SANITIZE_STRING);
$valor = filter_input(INPUT_GET, 'valor', FILTER_SANITIZE_STRING);
$tempo = filter_input(INPUT_GET, 'tempo', FILTER_SANITIZE_STRING);

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // CONSULTA APENAS COM CAMPOS EXISTENTES
    $sql = "SELECT 
                id, 
                nome_completo AS nome, 
                apelido, 
                data_nascimento,
                DATE_FORMAT(data_desaparecimento, '%d/%m/%Y') AS desaparecidoEm,
                data_desaparecimento AS dataOriginal,
                cidade, 
                estado, 
                foto, 
                status,
                vestimentas,
                caracteristicas,
                contatoFamilia,
                ultimoLocalVisto
            FROM desaparecidos
            WHERE status = 'desaparecido'";
    
    $params = [];
    
    // Verificação de filtros de busca
    if ($filtro && $valor !== null) {
        switch ($filtro) {
            case 'nome':
                $sql .= " AND (nome_completo LIKE :valor OR apelido LIKE :valor)";
                $params[':valor'] = "%$valor%";
                break;
            case 'cidade':
                $sql .= " AND cidade LIKE :valor";
                $params[':valor'] = "%$valor%";
                break;
            case 'idade':
                if (is_numeric($valor)) {
                    $sql .= " AND TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) = :valor";
                    $params[':valor'] = (int)$valor;
                }
                break;
        }
    }
    
    // Ordenando os resultados
    $sql .= " ORDER BY data_desaparecimento DESC";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Aplicar filtro de tempo se necessário
    if ($filtro === 'tempo' && $tempo) {
        $resultados = array_filter($resultados, function($pessoa) use ($tempo) {
            if (empty($pessoa['dataOriginal'])) return false;
            
            try {
                $hoje = new DateTime();
                $dataDes = DateTime::createFromFormat('Y-m-d', $pessoa['dataOriginal']);
                if (!$dataDes) return false;
                
                $dias = $dataDes->diff($hoje)->days;
                
                switch ($tempo) {
                    case '1 semana': return $dias <= 7;
                    case '1 mes': return $dias <= 30;
                    case '3 meses': return $dias <= 90;
                    case '6 meses': return $dias <= 180;
                    case '1 ano': return $dias <= 365;
                    case '2 anos+': return $dias > 730;
                    default: return false;
                }
            } catch (Exception $e) {
                return false;
            }
        });
        $resultados = array_values($resultados);
    }
    
    // Resposta final em JSON
    echo json_encode(utf8ize($resultados), JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['erro' => 'Erro na requisição: ' . $e->getMessage()]);
}
?>
