<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$jsonFile = __DIR__ . '/../../../db/desaparecidos.json';

try {
    // Verificar se o arquivo existe
    if (!file_exists($jsonFile)) {
        throw new Exception('Arquivo de dados não encontrado');
    }

    // Ler os dados do JSON
    $jsonContent = file_get_contents($jsonFile);
    $data = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
    }

    // Obter filtros do POST
    $filtros = json_decode(file_get_contents('php://input'), true)['filtros'] ?? [];

    // Aplicar filtros
    $filteredData = array_filter($data, function($item) use ($filtros) {
        // Filtro por nome
        if (!empty($filtros['nome']) && stripos($item['nome'], $filtros['nome']) === false) {
            return false;
        }
        
        // Filtro por idade
        if (!empty($filtros['idadeMin']) && intval($item['idade']) < intval($filtros['idadeMin'])) {
            return false;
        }
        if (!empty($filtros['idadeMax']) && intval($item['idade']) > intval($filtros['idadeMax'])) {
            return false;
        }
        
        // Filtro por sexo
        if (!empty($filtros['sexo']) && (!isset($item['sexo']) || $item['sexo'] !== $filtros['sexo'])) {
            return false;
        }
        
        // Filtro por data
        if (!empty($filtros['dataInicio']) || !empty($filtros['dataFim'])) {
            $dataDesaparecimento = DateTime::createFromFormat('d/m/Y', $item['desaparecidoEm']);
            if (!$dataDesaparecimento) {
                return false;
            }
            
            if (!empty($filtros['dataInicio'])) {
                $dataInicio = new DateTime($filtros['dataInicio']);
                if ($dataDesaparecimento < $dataInicio) {
                    return false;
                }
            }
            
            if (!empty($filtros['dataFim'])) {
                $dataFim = new DateTime($filtros['dataFim']);
                if ($dataDesaparecimento > $dataFim) {
                    return false;
                }
            }
        }
        
        // Filtro por estado/cidade
        if (!empty($filtros['estado']) && (!isset($item['estado']) || $item['estado'] !== $filtros['estado'])) {
            return false;
        }
        if (!empty($filtros['cidade']) && (!isset($item['cidade']) || stripos($item['cidade'], $filtros['cidade']) === false)) {
            return false;
        }
        
        // Filtro por características físicas
        if (!empty($filtros['corOlhos']) && (!isset($item['corOlhos']) || $item['corOlhos'] !== $filtros['corOlhos'])) {
            return false;
        }
        if (!empty($filtros['corPele']) && (!isset($item['corPele']) || $item['corPele'] !== $filtros['corPele'])) {
            return false;
        }
        if (!empty($filtros['tipoCabelo']) && (!isset($item['tipoCabelo']) || $item['tipoCabelo'] !== $filtros['tipoCabelo'])) {
            return false;
        }
        
        return true;
    });

    // Preparar resposta
    echo json_encode([
        'data' => array_values($filteredData), // Reindexar array
        'recordsTotal' => count($data),
        'recordsFiltered' => count($filteredData),
        'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 1
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}