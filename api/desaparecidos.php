<?php
require_once '../includes/db.php';

$filtro = $_GET['filtro'] ?? null;
$valor = $_GET['valor'] ?? null;

try {
    if ($filtro && $valor) {
        if ($filtro === 'tempo') {
            // Tempo será tratado em PHP
            $stmt = $pdo->prepare("
                SELECT id, nome_completo AS nome, apelido, DATE_FORMAT(data_desaparecimento, '%d/%m/%Y') AS desaparecidoEm,
                       cidade, estado, foto, status, data_desaparecimento
                FROM desaparecidos
                WHERE status = 'desaparecido'
            ");
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Aplica filtro de tempo em PHP
            $filtrados = array_filter($dados, function($pessoa) use ($valor) {
                $hoje = new DateTime();
                $data = DateTime::createFromFormat('d/m/Y', $pessoa['desaparecidoEm']);
                if (!$data) return false;
                $dias = $hoje->diff($data)->days;

                return match ($valor) {
                    '1 semana'   => $dias <= 7,
                    '1 mes'      => $dias <= 30,
                    '3 meses'    => $dias <= 90,
                    '6 meses'    => $dias <= 180,
                    '1 ano'      => $dias <= 365,
                    '2 anos+'    => $dias > 730,
                    default      => false
                };
            });

            header('Content-Type: application/json');
            echo json_encode(array_values($filtrados));
            exit;
        }

        // Segurança: só aceita campos permitidos
        switch ($filtro) {
            case 'nome':
                $sql = "nome_completo LIKE :valor";
                break;
            case 'cidade':
                $sql = "cidade LIKE :valor";
                break;
            case 'idade':
                $sql = "TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) = :valor";
                $valor = (int) $valor;
                break;
            default:
                throw new Exception("Filtro inválido.");
        }

        $stmt = $pdo->prepare("
            SELECT id, nome_completo AS nome, apelido, data_nascimento,
                   DATE_FORMAT(data_desaparecimento, '%d/%m/%Y') AS desaparecidoEm,
                   cidade, estado, foto, status
            FROM desaparecidos
            WHERE status = 'desaparecido' AND $sql
        ");

        // Aplica bind corretamente
        if ($filtro === 'idade') {
            $stmt->bindValue(':valor', $valor, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':valor', "%$valor%", PDO::PARAM_STR);
        }

    } else {
        // Sem filtro, lista todos
        $stmt = $pdo->prepare("
            SELECT id, nome_completo AS nome, apelido, data_nascimento,
                   DATE_FORMAT(data_desaparecimento, '%d/%m/%Y') AS desaparecidoEm,
                   cidade, estado, foto, status
            FROM desaparecidos
            WHERE status = 'desaparecido'
            ORDER BY data_desaparecimento DESC
        ");
    }

    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($resultados);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro na consulta: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['erro' => 'Requisição inválida: ' . $e->getMessage()]);
}
