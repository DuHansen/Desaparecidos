<?php
require_once '../../includes/db.php'; // Caminho correto para o arquivo db.php

header('Content-Type: application/json');

try {
    // Consulta para contar o número de desaparecidos por estado
    $stmt = $pdo->prepare("
        SELECT estado, COUNT(*) AS total
        FROM desaparecidos
        GROUP BY estado
    ");
    
    $stmt->execute();
    
    // Obtendo os resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializando os valores para as categorias (estados) e totais
    $estados = [];
    $totais = [];

    // Preenchendo os dados para os estados e totais
    foreach ($result as $row) {
        $estados[] = $row['estado'];
        $totais[] = (int)$row['total'];
    }

    // Retornando os dados no formato JSON
    echo json_encode([
        'estados' => $estados,
        'totais' => $totais
    ]);

} catch (PDOException $e) {
    // Caso haja erro no banco de dados
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>
