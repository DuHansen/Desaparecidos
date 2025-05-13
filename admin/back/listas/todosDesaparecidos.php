<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Caminho para o arquivo JSON
$jsonFile = __DIR__ . '/../../../db/desaparecidos.json';

try {
    // Verificar se o arquivo existe
    if (!file_exists($jsonFile)) {
        throw new Exception('Arquivo de dados não encontrado');
    }

    // Ler o conteúdo do arquivo JSON
    $jsonContent = file_get_contents($jsonFile);
    $data = json_decode($jsonContent, true);

    // Verificar se o JSON é válido
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
    }

    // Parâmetros da requisição (para DataTables)
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length = isset($_GET['length']) ? intval($_GET['length']) : 30;
    $search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $orderColumn = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 0;
    $orderDir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc';

    // Mapeamento das colunas para ordenação
    $columns = [
        0 => 'nome',
        1 => 'idade',
        2 => 'desaparecidoEm',
        3 => 'cidade'
    ];

    // Aplicar filtro de busca se existir
    if (!empty($search)) {
        $filteredData = array_filter($data, function($item) use ($search) {
            return stripos($item['nome'], $search) !== false || 
                   stripos($item['cidade'], $search) !== false ||
                   stripos($item['idade'], $search) !== false ||
                   stripos($item['desaparecidoEm'], $search) !== false;
        });
        $data = array_values($filteredData); // Reindexar o array
    }

    // Ordenar os dados
    if (isset($columns[$orderColumn])) {
        $column = $columns[$orderColumn];
        usort($data, function($a, $b) use ($column, $orderDir) {
            if ($orderDir === 'asc') {
                return $a[$column] <=> $b[$column];
            } else {
                return $b[$column] <=> $a[$column];
            }
        });
    }

    // Paginar os dados
    $paginatedData = array_slice($data, $start, $length);

    // Preparar resposta no formato esperado pelo DataTables
    $response = [
        'draw' => $draw,
        'recordsTotal' => count($data),
        'recordsFiltered' => count($data),
        'data' => $paginatedData
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}