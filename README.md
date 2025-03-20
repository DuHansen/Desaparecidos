### Pessoas desaparecidas em Santa Catarina 

## Classe No (Nó da Árvore Binária)
'
class No {
    public $pessoa;
    public $esquerda;
    public $direita;

    public function __construct($pessoa) {
        $this->pessoa = $pessoa;
        $this->esquerda = null;
        $this->direita = null;
    }
}
'
O que faz?
Representa um nó (ou nódulo) de uma árvore binária.

Cada nó armazena:

$pessoa: Os dados da pessoa desaparecida (nome, idade, cidade, etc.).

$esquerda: Referência para o nó filho à esquerda.

$direita: Referência para o nó filho à direita.

O construtor (__construct) inicializa o nó com os dados da pessoa e define os filhos como null (não existem filhos no início).

### Classe ArvoreBinaria (Árvore Binária)

class ArvoreBinaria {
    private $raiz;

    public function __construct() {
        $this->raiz = null;
    }
}

O que faz?
Representa a árvore binária em si.

A árvore começa com a raiz ($raiz) definida como null (árvore vazia).

## Método inserir

public function inserir($pessoa) {
    $this->raiz = $this->inserirRec($this->raiz, $pessoa);
}

O que faz?
Insere uma nova pessoa na árvore binária.

Chama o método inserirRec para realizar a inserção de forma recursiva.

## Método inserirRec (Inserção Recursiva)

private function inserirRec($no, $pessoa) {
    if ($no === null) {
        return new No($pessoa);
    }

    if (strcasecmp($pessoa['nome'], $no->pessoa['nome']) < 0) {
        $no->esquerda = $this->inserirRec($no->esquerda, $pessoa);
    } else {
        $no->direita = $this->inserirRec($no->direita, $pessoa);
    }

    return $no;
}

O que faz?
Insere uma pessoa na árvore de forma recursiva.

Se o nó atual ($no) for null, cria um novo nó com os dados da pessoa.

Caso contrário, compara o nome da pessoa com o nome do nó atual:

Se o nome for "menor" (em ordem alfabética), insere no filho à esquerda.

Se o nome for "maior" ou igual, insere no filho à direita.

Retorna o nó atual após a inserção.

## Método buscarPorFiltro

O que faz?
Inicia a busca na árvore com base em um filtro (nome, cidade, idade, tempo de desaparecimento) e um valor.

Chama o método buscarPorFiltroRec para realizar a busca de forma recursiva.

## Método buscarPorFiltroRec (Busca Recursiva)

private function buscarPorFiltroRec($no, $filtro, $valor) {
    if ($no === null) {
        return [];
    }

    $resultados = [];
    if ($this->compararFiltro($no->pessoa, $filtro, $valor)) {
        $resultados[] = $no->pessoa;
    }

    // Buscar nos filhos
    $resultados = array_merge($resultados, $this->buscarPorFiltroRec($no->esquerda, $filtro, $valor));
    $resultados = array_merge($resultados, $this->buscarPorFiltroRec($no->direita, $filtro, $valor));

    return $resultados;
}

O que faz?
Realiza a busca na árvore de forma recursiva.

Se o nó atual ($no) for null, retorna um array vazio (não há resultados).

Verifica se o nó atual corresponde ao filtro usando o método compararFiltro.

Se corresponder, adiciona a pessoa ao array de resultados.

Continua a busca nos filhos à esquerda e à direita.

Retorna todos os resultados encontrados.


## Método compararFiltro

private function compararFiltro($pessoa, $filtro, $valor) {
    switch ($filtro) {
        case 'nome':
            // Comparação parcial (case-insensitive)
            return stripos($pessoa['nome'], $valor) !== false;
        case 'cidade':
            return stripos($pessoa['cidade'], $valor) !== false;
        case 'idade':
            return $pessoa['idade'] == $valor;
        case 'tempo':
            return $this->compararTempoDesaparecimento($pessoa['desaparecidoEm'], $valor);
        default:
            return false;
    }
}

O que faz?
Compara os dados da pessoa com o filtro e o valor fornecidos.

Dependendo do filtro:

Nome: Verifica se o valor está contido no nome da pessoa (comparação parcial e sem diferenciar maiúsculas/minúsculas).

Cidade: Verifica se o valor está contido na cidade da pessoa.

Idade: Verifica se a idade da pessoa é igual ao valor.

Tempo: Chama o método compararTempoDesaparecimento para verificar o intervalo de tempo.

Retorna true se a pessoa corresponder ao filtro, caso contrário, retorna false.

## Método compararTempoDesaparecimento

private function compararTempoDesaparecimento($dataDesaparecimento, $tempo) {
    $dataAtual = new DateTime(); // Data atual
    $dataDesaparecimento = DateTime::createFromFormat('d/m/Y', $dataDesaparecimento); // Converte a data do desaparecimento

    if (!$dataDesaparecimento) {
        return false; // Se a data for inválida, retorna falso
    }

    $intervalo = $dataAtual->diff($dataDesaparecimento); // Calcula a diferença entre as datas

    switch ($tempo) {
        case '1 semana':
            return $intervalo->days <= 7 && $dataDesaparecimento <= $dataAtual;
        case '1 mes':
            return $intervalo->days <= 30 && $dataDesaparecimento <= $dataAtual;
        case '3 meses':
            return $intervalo->days <= 90 && $dataDesaparecimento <= $dataAtual;
        case '6 meses':
            return $intervalo->days <= 180 && $dataDesaparecimento <= $dataAtual;
        case '1 ano':
            return $intervalo->y < 1 && $dataDesaparecimento <= $dataAtual;
        case '2 anos+':
            return $intervalo->y >= 2 && $dataDesaparecimento <= $dataAtual;
        default:
            return false;
    }
}

O que faz?
Compara a data de desaparecimento com o intervalo de tempo especificado.

Converte a data de desaparecimento (no formato dd/mm/aaaa) para um objeto DateTime.

Calcula a diferença entre a data atual e a data de desaparecimento.

Dependendo do intervalo de tempo selecionado (1 semana, 1 mês, etc.), verifica se a diferença está dentro do limite.

Retorna true se a data de desaparecimento estiver dentro do intervalo, caso contrário, retorna false.

## Leitura do JSON e Inserção na Árvore

O que faz?
Lê os dados de um arquivo JSON (desaparecidos.json) e converte-os em um array associativo.

Cria uma instância da árvore binária (ArvoreBinaria).

Insere cada pessoa da lista na árvore.

##  Busca com Base no Filtro

O que faz?
Verifica se um filtro foi enviado via método GET.

Se o filtro for "tempo", usa o valor do campo tempo.

Realiza a busca na árvore com base no filtro e no valor.

Armazena os resultados no array $resultados.







