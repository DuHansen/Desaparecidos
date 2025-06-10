<?php
require_once '../../includes/db.php'; // Caminho correto para o arquivo db.php

header('Content-Type: application/json');

try {
    // Consulta para contar os desaparecidos agrupados por status
    $stmt = $pdo->prepare("
        SELECT status, COUNT(*) AS total
        FROM desaparecidos
        GROUP BY status
    ");
    
    $stmt->execute();
    
    // Obtendo os resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializando os valores para "Encontrados" e "Desaparecidos"
    $encontrados = 0;
    $desaparecidos = 0;

    // Preenchemos os valores para cada status
    foreach ($result as $row) {
        if ($row['status'] == 'encontrado_vivo' || $row['status'] == 'encontrado_falecido') {
            $encontrados += $row['total'];
        } elseif ($row['status'] == 'desaparecido') {
            $desaparecidos += $row['total'];
        }
    }

    // Retornando os dados no formato JSON
    echo json_encode([
        'encontrados' => $encontrados,
        'desaparecidos' => $desaparecidos
    ]);

} catch (PDOException $e) {
    // Caso haja erro no banco de dados
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>
