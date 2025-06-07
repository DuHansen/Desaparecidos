<?php
require_once '../../includes/db.php'; // ajuste o caminho conforme sua estrutura
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT 
            id,
            nome_completo AS nome_desaparecido,
            apelido,
            idade,
            sexo,
            data_nascimento,
            data_desaparecimento,
            cidade,
            ultimo_local,
            circunstancias,
            altura,
            peso,
            cor_pele,
            vestimentas,
            caracteristicas,
            telefone_contato,
            email,
            foto,
            status,
            data_criacao,

            -- Dados do denunciante
            denunciante_nome,
            denunciante_cpf,
            denunciante_email

        FROM denuncia
        ORDER BY data_criacao DESC
    ");
    
    $stmt->execute();
    $denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Se desejar converter blob para base64
    foreach ($denuncias as &$d) {
        $d['foto'] = base64_encode($d['foto']); // pode ser adaptado para path se estiver em arquivo
    }

    echo json_encode(['success' => true, 'data' => $denuncias]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
