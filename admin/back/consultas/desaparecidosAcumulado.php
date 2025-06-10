<?php
require_once '../../includes/db.php'; // Caminho correto para o arquivo db.php

header('Content-Type: application/json');

try {
    // Consulta SQL para contar os cadastros por mês e ano, a partir de 2020
    $stmt = $pdo->prepare("
        SELECT 
            YEAR(data_cadastro) AS ano,
            MONTH(data_cadastro) AS mes,
            COUNT(*) AS total
        FROM desaparecidos
        WHERE data_cadastro >= '2020-01-01'  -- Filtra para mostrar dados de 2020 em diante
        GROUP BY ano, mes
        ORDER BY ano ASC, mes ASC
    ");

    $stmt->execute();

    // Obtendo os resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializando os dados para os meses
    $labels = [];
    $totals = [];

    // Construir lista completa de meses desde Janeiro de 2020 até o mês atual
    $startYear = 2020;
    $endYear = (int)date("Y");  // Ano atual
    $endMonth = (int)date("m"); // Mês atual

    // Gerando todos os meses de 2020 até o mês atual
    for ($year = $startYear; $year <= $endYear; $year++) {
        for ($month = 1; $month <= 12; $month++) {
            if ($year == $endYear && $month > $endMonth) {
                break;  // Evita meses futuros
            }
            $labels[] = date("M Y", strtotime("$year-$month-01"));  // Formata como "Jan 2020"
            $totals[] = 0;  // Inicializa com 0, será substituído pelos dados se houver
        }
    }

    // Preenchendo os totais com os resultados da consulta
    foreach ($result as $row) {
        $yearMonth = date("M Y", strtotime($row['ano'] . '-' . $row['mes'] . '-01'));  // Formata como "Jan 2025"
        $index = array_search($yearMonth, $labels); // Encontra o índice correspondente ao mês/ano
        if ($index !== false) {
            $totals[$index] = (int)$row['total']; // Preenche o total de cada mês
        }
    }

    // Retornando os dados no formato JSON
    echo json_encode([
        'labels' => $labels,
        'data' => $totals
    ]);

} catch (PDOException $e) {
    // Caso haja erro no banco de dados
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>
