<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function carregarDadosDesaparecidos($caminhoArquivo) {
    // Verifica se o arquivo existe
    if (!file_exists($caminhoArquivo)) {
        echo "Arquivo não encontrado: " . $caminhoArquivo;
        return [];
    }

    // Tenta ler o conteúdo do arquivo
    $conteudo = file_get_contents($caminhoArquivo);
    if ($conteudo === false) {
        echo "Erro ao ler o arquivo: " . $caminhoArquivo;
        return [];
    }

    // Tenta decodificar o conteúdo JSON
    $dados = json_decode($conteudo, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Erro ao decodificar JSON: " . json_last_error_msg();
        return [];
    }

    return $dados;
}

function obtertotalDesaparecidos() {
    $dados = carregarDadosDesaparecidos(__DIR__ . '/../../../db/desaparecidos.json');
    return count($dados);
}

function desaparecidosPorDia() {
    $dados = carregarDadosDesaparecidos(__DIR__ . '/../../../db/desaparecidos.json');
    $contagemPorDia = [];

    foreach ($dados as $registro) {
        $data = $registro['desaparecidoEm'] ?? null;
        if ($data) {
            if (!isset($contagemPorDia[$data])) {
                $contagemPorDia[$data] = 0;
            }
            $contagemPorDia[$data]++;
        }
    }

    return $contagemPorDia;
}

function porcentagemCresciCadastrosPorDia() {
    $dadosPorDia = desaparecidosPorDia();
    ksort($dadosPorDia);
    $dias = array_keys($dadosPorDia);
    $percentuais = [];

    for ($i = 1; $i < count($dias); $i++) {
        $diaAnterior = $dias[$i - 1];
        $diaAtual = $dias[$i];
        $valorAnterior = $dadosPorDia[$diaAnterior];
        $valorAtual = $dadosPorDia[$diaAtual];

        if ($valorAnterior == 0) {
            $percentual = 0;
        } else {
            $percentual = (($valorAtual - $valorAnterior) / $valorAnterior) * 100;
        }

        $percentuais[$diaAtual] = round($percentual, 2);
    }

    return $percentuais;
}

function obterDadosConversao() {
    $dados = carregarDadosDesaparecidos(__DIR__ . '/../../../db/desaparecidos.json');
    $total = count($dados);
    $menoresDeIdade = 0;

    foreach ($dados as $registro) {
        $idade = isset($registro['idade']) ? (int)$registro['idade'] : null;
        if ($idade !== null && $idade < 18) {
            $menoresDeIdade++;
        }
    }

    $percentualMenores = $total > 0 ? ($menoresDeIdade / $total) * 100 : 0;

    return [
        'total' => $total,
        'menoresDeIdade' => $menoresDeIdade,
        'percentualMenores' => round($percentualMenores, 2)
    ];
}

// Exemplo de uso
$dados = carregarDadosDesaparecidos(__DIR__ . '/../../../db/desaparecidos.json');
if (!empty($dados)) {
    echo "Total de desaparecidos: " . count($dados) . "<br>";
    foreach ($dados as $registro) {
        echo "Nome: " . $registro['nome'] . "<br>";
        echo "Foto: <img src='" . $registro['foto'] . "' alt='" . $registro['nome'] . "'><br>";
        echo "Idade: " . $registro['idade'] . "<br>";
        echo "Desaparecido em: " . $registro['desaparecidoEm'] . "<br>";
        echo "Cidade: " . $registro['cidade'] . "<br><br>";
    }
} else {
    echo "Nenhum dado encontrado.";
}
?>
