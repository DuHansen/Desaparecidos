<?php include 'header.php'; ?>

<!-- Estilos e animações -->
<style>
    body {
        background: linear-gradient(135deg, #2a2a72, #009ffd);
        color: white;
        font-family: 'Arial', sans-serif;
    }

    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 15px;
        height: 100%; /* Todos os cards com mesma altura */
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 20px rgba(255, 255, 255, 0.2);
    }

    .card-img-top {
        height: 250px; /* Definir altura fixa para as imagens */
        object-fit: cover; /* Garante que a imagem preencha sem distorcer */
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .fade-in {
        opacity: 0;
        animation: fadeIn 1s ease-in-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .btn-danger {
        transition: background 0.3s ease-in-out;
    }

    .btn-danger:hover {
        background: #b30000;
    }
</style>

<main class="container py-5">
    <div class="text-center mb-4 fade-in">
        <h1 class="fw-bold">Lista de Desaparecidos</h1>
        <p class="lead">Caso tenha informações, entre em contato imediatamente.</p>
    </div>

    <!-- Formulário de Busca com Dropdown de Filtro -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <select class="form-select" name="filtro" id="filtroSelect" required>
                <option value="nome">Buscar por Nome</option>
                <option value="cidade">Filtrar por Cidade</option>
                <option value="idade">Filtrar por Idade</option>
                <option value="tempo">Filtrar por Tempo de Desaparecimento</option>
            </select>
            <select class="form-select" name="tempo" id="tempoFiltro" style="display: none;">
                <option value="1 semana">1 semana</option>
                <option value="1 mes">1 mês</option>
                <option value="3 meses">3 meses</option>
                <option value="6 meses">6 meses</option>
                <option value="1 ano">1 ano</option>
                <option value="2 anos+">2 anos ou mais</option>
            </select>
            <input type="text" class="form-control" name="valor" placeholder="Digite o valor para filtrar" id="valorFiltro" style="display: none;">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <script>
        // Mostrar/ocultar campos de filtro com base na seleção
        document.getElementById('filtroSelect').addEventListener('change', function() {
            const filtro = this.value;
            const tempoFiltro = document.getElementById('tempoFiltro');
            const valorFiltro = document.getElementById('valorFiltro');

            if (filtro === 'tempo') {
                tempoFiltro.style.display = 'block';
                valorFiltro.style.display = 'none';
                valorFiltro.removeAttribute('required'); // Remove o required do campo valor
                tempoFiltro.setAttribute('required', true); // Adiciona required ao campo tempo
            } else {
                tempoFiltro.style.display = 'none';
                valorFiltro.style.display = 'block';
                tempoFiltro.removeAttribute('required'); // Remove o required do campo tempo
                valorFiltro.setAttribute('required', true); // Adiciona required ao campo valor
            }
        });

        // Garantir que o campo correto esteja visível ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            const filtroSelect = document.getElementById('filtroSelect');
            const tempoFiltro = document.getElementById('tempoFiltro');
            const valorFiltro = document.getElementById('valorFiltro');

            if (filtroSelect.value === 'tempo') {
                tempoFiltro.style.display = 'block';
                valorFiltro.style.display = 'none';
                valorFiltro.removeAttribute('required');
                tempoFiltro.setAttribute('required', true);
            } else {
                tempoFiltro.style.display = 'none';
                valorFiltro.style.display = 'block';
                tempoFiltro.removeAttribute('required');
                valorFiltro.setAttribute('required', true);
            }
        });
    </script>

    <?php
    // Classe Nó da Árvore Binária
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

    // Classe Árvore Binária
    class ArvoreBinaria {
        private $raiz;

        public function __construct() {
            $this->raiz = null;
        }

        // Inserir uma pessoa na árvore
        public function inserir($pessoa) {
            $this->raiz = $this->inserirRec($this->raiz, $pessoa);
        }

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

        // Buscar pessoas com base em um filtro
        public function buscarPorFiltro($filtro, $valor) {
            return $this->buscarPorFiltroRec($this->raiz, $filtro, $valor);
        }

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
    }

    // Ler os dados do JSON
    $json = file_get_contents('desaparecidos.json');
    $desaparecidos = json_decode($json, true);

    // Criar árvore binária e inserir os dados
    $arvore = new ArvoreBinaria();
    foreach ($desaparecidos as $pessoa) {
        $arvore->inserir($pessoa);
    }

    // Verificar se foi enviado um filtro e valor para busca
    $resultados = [];
    if (isset($_GET['filtro']) && !empty($_GET['filtro'])) {
        $filtro = $_GET['filtro'];
        $valor = $_GET['valor'] ?? '';

        // Se o filtro for "tempo", usar o valor do campo "tempo"
        if ($filtro === 'tempo') {
            $valor = $_GET['tempo'] ?? '';
        }

        $resultados = $arvore->buscarPorFiltro($filtro, $valor);
    }

    // Função para exibir os cards
    function exibirCards($pessoas) {
        foreach ($pessoas as $pessoa): ?>
            <div class="col-sm-12 col-md-6 col-lg-4 fade-in">
                <div class="card h-100 shadow-lg">
                    <img src="<?= htmlspecialchars($pessoa['foto']) ?>" class="card-img-top" alt="<?= htmlspecialchars($pessoa['nome']) ?>">
                    <div class="card-body text-dark d-flex flex-column">
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($pessoa['nome']) ?></h5>
                        <p class="card-text flex-grow-1">
                            <strong>Idade:</strong> <?= htmlspecialchars($pessoa['idade']) ?><br>
                            <strong>Desaparecido em:</strong> <?= htmlspecialchars($pessoa['desaparecidoEm']) ?><br>
                            <strong>Cidade:</strong> <?= htmlspecialchars($pessoa['cidade']) ?>
                        </p>
                        <a href="#" class="btn btn-danger w-100 mt-auto">Reportar Informações</a>
                    </div>
                </div>
            </div>
        <?php endforeach;
    }
    ?>

    <!-- Exibir resultados da busca ou a lista completa -->
    <div class="row g-4">
        <?php if (!empty($resultados)): ?>
            <!-- Mostrar resultados da busca -->
            <?php exibirCards($resultados); ?>
        <?php else: ?>
            <!-- Mostrar lista completa de desaparecidos -->
            <?php exibirCards($desaparecidos); ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>