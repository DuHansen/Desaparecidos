<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$jsonFile = __DIR__ . '/../../../db/desaparecidos.json';

try {
    if (!isset($_GET['id'])) {
        throw new Exception('ID não fornecido');
    }

    $id = $_GET['id'];
    
    if (!file_exists($jsonFile)) {
        throw new Exception('Arquivo de dados não encontrado');
    }

    $jsonContent = file_get_contents($jsonFile);
    $data = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
    }

    // Encontrar o registro pelo ID
    $registro = null;
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            $registro = $item;
            break;
        }
    }

    if (!$registro) {
        throw new Exception('Registro não encontrado');
    }

    echo json_encode([
        'success' => true,
        'data' => $registro
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}