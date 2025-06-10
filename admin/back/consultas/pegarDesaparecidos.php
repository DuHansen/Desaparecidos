<?php
require_once '../includes/db.php'; // Caminho correto para o arquivo db.php

// Função para obter os totais e as porcentagens
function getDashboardData() {
    global $pdo;

    try {
        // Total de Desaparecidos
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS total FROM desaparecidos WHERE status = 'desaparecido'");
        $stmtTotal->execute();
        $resultTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalDesaparecidos = $resultTotal['total'];

        // Total de Pessoas Encontradas (status diferente de 'desaparecido')
        $stmtEncontrados = $pdo->prepare("SELECT COUNT(*) AS total FROM desaparecidos WHERE status != 'desaparecido'");
        $stmtEncontrados->execute();
        $resultEncontrados = $stmtEncontrados->fetch(PDO::FETCH_ASSOC);
        $totalEncontrados = $resultEncontrados['total'];

        // Desaparecidos Recentes (últimos 7 dias)
        $stmtRecent = $pdo->prepare("SELECT COUNT(*) AS total_recent FROM desaparecidos WHERE data_desaparecimento >= CURDATE() - INTERVAL 7 DAY");
        $stmtRecent->execute();
        $resultRecent = $stmtRecent->fetch(PDO::FETCH_ASSOC);
        $totalRecent = $resultRecent['total_recent'];

        // Taxa de Resolução
        $stmtResolution = $pdo->prepare("SELECT (COUNT(CASE WHEN status != 'desaparecido' THEN 1 END) / COUNT(*)) * 100 AS taxa_resolucao FROM desaparecidos");
        $stmtResolution->execute();
        $resultResolution = $stmtResolution->fetch(PDO::FETCH_ASSOC);
        $taxaResolucao = $resultResolution['taxa_resolucao'];

        // Calculando as porcentagens para os cards
        $percentageRecent = ($totalRecent / $totalDesaparecidos) * 100;
        $percentageResolution = $taxaResolucao;

        return [
            'totalDesaparecidos' => $totalDesaparecidos,
            'totalEncontrados' => $totalEncontrados,
            'totalRecent' => $totalRecent,
            'taxaResolucao' => round($taxaResolucao, 2),
            'percentageRecent' => round($percentageRecent, 2),
            'percentageResolution' => round($percentageResolution, 2)
        ];

    } catch (PDOException $e) {
        // Em caso de erro no banco de dados
        return [
            'totalDesaparecidos' => 0,
            'totalEncontrados' => 0,
            'totalRecent' => 0,
            'taxaResolucao' => 0,
            'percentageRecent' => 0,
            'percentageResolution' => 0
        ]; // Retorna 0 em caso de erro
    }
}

// Obter os dados para os cards
$dashboardData = getDashboardData();
?>

