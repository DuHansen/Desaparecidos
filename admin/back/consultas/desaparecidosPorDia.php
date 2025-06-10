<?php
require_once '../../includes/db.php'; // Caminho correto para o arquivo db.php

header('Content-Type: application/json');

try {
    // Data de hoje
    $today = date('Y-m-d');
    
    // Calculando a data de 30 dias atrás
    $startDate = date('Y-m-d', strtotime('-30 days', strtotime($today)));
    
    // Consulta para pegar os desaparecidos por dia nos últimos 30 dias
    $stmt = $pdo->prepare("
        SELECT DATE(data_desaparecimento) AS dia, COUNT(*) AS total
        FROM desaparecidos
        WHERE data_desaparecimento BETWEEN :startDate AND :today
        GROUP BY dia
        ORDER BY dia DESC
    ");
    
    // Executando a consulta com as datas de início e fim
    $stmt->execute([':startDate' => $startDate, ':today' => $today]);
    
    // Obtendo os resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Preenchendo dias ausentes com 0
    $allDates = [];
    $data = [];
    $labels = [];

    // Preencher todos os dias dos últimos 30 dias
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $allDates[] = $date;
        $labels[] = date('d/m', strtotime($date)); // Apenas o dia/mês

        $data[$date] = 0; // Inicializa com 0
    }

    // Preenchendo os dados reais da consulta
    foreach ($result as $row) {
        $data[$row['dia']] = (int)$row['total'];
    }

    // Organizando os dados para o gráfico
    $finalData = [];
    foreach ($allDates as $date) {
        $finalData[] = $data[$date];
    }

    // Retornando os dados no formato JSON
    echo json_encode([
        'labels' => $labels,
        'data' => $finalData
    ]);

} catch (PDOException $e) {
    // Caso haja erro no banco de dados
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>
