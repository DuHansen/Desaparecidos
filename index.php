<?php 
include 'includes/header.php';

// Configurações
$itensPorPagina = 12;
$paginaAtual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$valor = isset($_GET['valor']) ? $_GET['valor'] : '';
$tempo = isset($_GET['tempo']) ? $_GET['tempo'] : '';

// Função para buscar desaparecidos na API com filtros e paginação
function buscarDesaparecidosAPI($pagina = 1, $itensPorPagina = 12, $filtro = '', $valor = '', $tempo = '') {
    $url = 'http://localhost:8000/api/desaparecidos?';
    $params = [];
    
    if ($filtro && ($valor || $tempo)) {
        $params['filtro'] = $filtro;
        if ($filtro === 'tempo') {
            $params['tempo'] = $tempo;
        } else {
            $params['valor'] = $valor;
        }
    }
    
    $params['page'] = $pagina;
    $params['limit'] = $itensPorPagina;
    
    $url .= http_build_query($params);
    
    try {
        $response = file_get_contents($url);
        return json_decode($response, true);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

// Buscar dados
$dados = buscarDesaparecidosAPI($paginaAtual, $itensPorPagina, $filtro, $valor, $tempo);
$pessoas = $dados['data'] ?? [];
$totalPessoas = $dados['total'] ?? 0;
$totalPaginas = ceil($totalPessoas / $itensPorPagina);

// Funções auxiliares
function calcularIdade($dataNascimento) {
    if (!$dataNascimento) return '-';
    try {
        $nascimento = new DateTime($dataNascimento);
        $hoje = new DateTime();
        $idade = $hoje->diff($nascimento)->y;
        return $idade < 1 ? 'menos de 1 ano' : $idade . ' anos';
    } catch (Exception $e) {
        return '-';
    }
}

function calcularTempoDesaparecimento($dataStr) {
    if (!$dataStr) return 'tempo desconhecido';
    
    try {
        // Tenta formatar a data no formato DD/MM/YYYY
        if (preg_match('#(\d{2})/(\d{2})/(\d{4})#', $dataStr, $matches)) {
            $data = new DateTime("{$matches[3]}-{$matches[2]}-{$matches[1]}");
        } else {
            $data = new DateTime($dataStr);
        }
        
        $hoje = new DateTime();
        $diferenca = $hoje->diff($data);
        
        if ($diferenca->y > 0) return $diferenca->y . ' ano' . ($diferenca->y > 1 ? 's' : '');
        if ($diferenca->m > 0) return $diferenca->m . ' mês' . ($diferenca->m > 1 ? 'es' : '');
        if ($diferenca->d > 7) return floor($diferenca->d/7) . ' semana' . (floor($diferenca->d/7) > 1 ? 's' : '');
        if ($diferenca->d > 0) return $diferenca->d . ' dia' . ($diferenca->d > 1 ? 's' : '');
        
        return 'menos de 24h';
    } catch (Exception $e) {
        return 'tempo desconhecido';
    }
}
?>
<?php
// URL da API
$url = 'http://localhost:8000/desaparecidos/recentes';

// Faz requisição GET à API
$response = file_get_contents($url);

// Converte o JSON em array associativo
$data = json_decode($response, true);

// Verifica se há dados
$desaparecidos = $data['data'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Desaparecidos SC - Ajude a Encontrar</title>
  <meta name="description" content="Portal de pessoas desaparecidas em Santa Catarina. Ajude a reunir famílias.">
  <link href="assets/css/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/css/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>

<!-- Hero Section -->
<div class="position-relative" style="height: 70vh;">
  <img src="assets/img/bg.png" alt="Background"
       class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover z-0">
  <div class="position-relative z-1 h-100 d-flex align-items-center justify-content-center text-center px-3">
    <div>
      <h1 class="fw-bold text-danger">Desaparecidos em Santa Catarina</h1>
      <p class="lead">Ajude a reunir famílias. Qualquer informação pode ser crucial.</p>
      
      <div class="alert alert-warning d-inline-flex align-items-center justify-content-center mt-3">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Caso tenha qualquer informação, entre em contato com a polícia (190) ou disque-denúncia (181).
      </div>
      
      <div class="mt-4">
        <a href="reportar.php" class="btn btn-outline-light px-4 py-2">
          <i class="bi bi-person-plus"></i> Reportar Desaparecimento
        </a>
      </div>
    </div>
  </div>
</div>

<main class="py-5">
  <!-- Alerta de erro -->
  <?php if (isset($dados['error'])): ?>
  <div class="alert alert-danger">
    <i class="bi bi-exclamation-octagon-fill"></i> Erro ao carregar dados da API: <?= htmlspecialchars($dados['error']) ?>
  </div>
  <?php endif; ?>

<section class="container mb-5">
  <h2 class="h4 mb-3 text-muted">
    <i class="bi bi-clock-history me-2"></i>Casos recentes
  </h2>
  <div id="carouselDesaparecidos" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
    <div class="carousel-inner rounded">
      <?php foreach ($desaparecidos as $index => $pessoa): ?>
        <?php
          $nome = $pessoa['nome_completo'] ?? 'Nome não informado';
          $foto = $pessoa['foto'] ?? 'assets/img/placeholder.jpg';
          $cidade = $pessoa['cidade'] ?? '-';
          $dataDesap = explode(' ', $pessoa['data_desaparecimento'] ?? '')[0] ?? '-';
          $idade = calcularIdade($pessoa['data_nascimento'] ?? null);
          $tempo = calcularTempoDesaparecimento($pessoa['data_desaparecimento'] ?? null);
        ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <div class="d-flex justify-content-center p-3 bg-light">
            <div class="card text-center" style="max-width: 400px;">
              <div class="overflow-hidden" style="height: 300px;">
                <img src="<?= htmlspecialchars($foto) ?>" class="w-100 h-100" style="object-fit: cover;" alt="Foto de <?= htmlspecialchars($nome) ?>" onerror="this.src='assets/img/placeholder.jpg'">
              </div>
              <div class="card-body">
                <h3 class="card-title h5"><?= htmlspecialchars($nome) ?></h3>
                <div class="card-text text-start">
                  <p><strong>Idade:</strong> <?= $idade ?></p>
                  <p><strong>Desaparecido em:</strong> <?= htmlspecialchars($dataDesap) ?></p>
                  <p><strong>Local:</strong> <?= htmlspecialchars($cidade) ?></p>
                  <p class="text-danger"><strong>Há:</strong> <?= $tempo ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselDesaparecidos" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselDesaparecidos" data-bs-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
    </button>
  </div>
</section>


<!-- 🔧 ADICIONE ESTA LINHA ABAIXO -->
<div id="modaisDesaparecidos"></div>


  <!-- Filtro de busca -->
  <section class="container bg-light p-4 rounded-3 shadow-sm mb-5">
    <h2 class="h4 mb-4"><i class="bi bi-search me-2"></i>Buscar Desaparecidos</h2>
    <form method="get" class="needs-validation" novalidate>
      <input type="hidden" name="pagina" value="1">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="filtroSelect" class="form-label">Filtrar por</label>
          <select class="form-select" id="filtroSelect" name="filtro" required>
            <option value="" disabled <?= !$filtro ? 'selected' : '' ?>>Selecione...</option>
            <option value="nome_completo" <?= $filtro === 'nome_completo' ? 'selected' : '' ?>>Nome</option>
            <option value="cidade" <?= $filtro === 'cidade' ? 'selected' : '' ?>>Cidade</option>
            <option value="idade" <?= $filtro === 'idade' ? 'selected' : '' ?>>Idade</option>
            <option value="tempo" <?= $filtro === 'tempo' ? 'selected' : '' ?>>Tempo de Desaparecimento</option>
          </select>
        </div>
        <div class="col-md-4" id="campoValor" style="<?= $filtro === 'tempo' ? 'display: none;' : '' ?>">
          <label for="valorFiltro" class="form-label">Valor</label>
          <input type="text" class="form-control" id="valorFiltro" name="valor" value="<?= htmlspecialchars($valor) ?>" <?= $filtro !== 'tempo' ? 'required' : '' ?>>
        </div>
        <div class="col-md-4" id="campoTempo" style="<?= $filtro !== 'tempo' ? 'display: none;' : '' ?>">
          <label for="tempoFiltro" class="form-label">Tempo</label>
          <select class="form-select" id="tempoFiltro" name="tempo" <?= $filtro === 'tempo' ? 'required' : '' ?>>
            <option value="" disabled <?= !$tempo ? 'selected' : '' ?>>Selecione...</option>
            <option value="1 semana" <?= $tempo === '1 semana' ? 'selected' : '' ?>>1 semana</option>
            <option value="1 mes" <?= $tempo === '1 mes' ? 'selected' : '' ?>>1 mês</option>
            <option value="3 meses" <?= $tempo === '3 meses' ? 'selected' : '' ?>>3 meses</option>
            <option value="6 meses" <?= $tempo === '6 meses' ? 'selected' : '' ?>>6 meses</option>
            <option value="1 ano" <?= $tempo === '1 ano' ? 'selected' : '' ?>>1 ano</option>
            <option value="2 anos+" <?= $tempo === '2 anos+' ? 'selected' : '' ?>>2 anos ou mais</option>
          </select>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-1"></i> Buscar
          </button>
        </div>
      </div>
    </form>
  </section>

  <!-- Resultados da busca -->
  <section class="container">
    <h2 class="h4 mb-3" id="tituloResultados">
      <i class="bi bi-people-fill me-2"></i> <?= $filtro ? 'Resultados da Busca' : 'Pessoas Desaparecidas' ?>
      <span class="badge bg-danger ms-2" id="contadorResultados"><?= $totalPessoas ?></span>
    </h2>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="listaDesaparecidos">
      <?php if (empty($pessoas)): ?>
        <div class="col-12">
          <div class="alert alert-info">Nenhum registro encontrado.</div>
        </div>
      <?php else: ?>
        <?php foreach ($pessoas as $index => $pessoa): ?>
          <?php 
            $idade = calcularIdade($pessoa['data_nascimento'] ?? '');
            $tempo = calcularTempoDesaparecimento($pessoa['data_desaparecimento'] ?? '');
            $idModal = 'modal-' . ($pessoa['id'] ?? $index);
          ?>
          <div class="col">
            <div class="card h-100 shadow-sm">
              <div class="overflow-hidden" style="height: 300px;">
                <img src="<?= htmlspecialchars($pessoa['foto'] ?? 'assets/img/placeholder.jpg') ?>"
                     class="card-img-top w-100 h-100" style="object-fit: cover;" 
                     alt="Foto de <?= htmlspecialchars($pessoa['nome_completo'] ?? 'pessoa desaparecida') ?>"
                     onerror="this.src='assets/img/placeholder.jpg'">
              </div>
              <div class="card-body d-flex flex-column">
                <h3 class="card-title h5"><?= htmlspecialchars($pessoa['nome_completo'] ?? 'Nome não informado') ?></h3>
                <p><strong>Idade:</strong> <?= $idade ?></p>
                <p><strong>Desaparecido em:</strong> <?= htmlspecialchars($pessoa['data_desaparecimento'] ?? '-') ?></p>
                <p><strong>Cidade:</strong> <?= htmlspecialchars($pessoa['cidade'] ?? '-') ?></p>
                <p class="text-danger"><strong>Há:</strong> <?= $tempo ?></p>
                <button class="btn btn-outline-danger mt-auto" data-bs-toggle="modal" data-bs-target="#<?= $idModal ?>">
                  <i class="bi bi-info-circle"></i> Detalhes
                </button>
              </div>
            </div>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="<?= $idModal ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                  <h5 class="modal-title"><?= htmlspecialchars($pessoa['nome_completo'] ?? 'Nome não informado') ?></h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                  <div class="col-md-6">
                    <img src="<?= htmlspecialchars($pessoa['foto'] ?? 'assets/img/placeholder.jpg') ?>"
                         class="img-fluid rounded mb-3"
                         onerror="this.src='assets/img/placeholder.jpg'">
                  </div>
                  <div class="col-md-6">
                    <ul class="list-group">
                      <li class="list-group-item"><strong>Idade:</strong> <?= $idade ?></li>
                      <li class="list-group-item"><strong>Desaparecido em:</strong> <?= htmlspecialchars($pessoa['data_desaparecimento'] ?? '-') ?></li>
                      <li class="list-group-item"><strong>Local:</strong> <?= htmlspecialchars($pessoa['cidade'] ?? '-') ?></li>
                      <li class="list-group-item"><strong>Há:</strong> <?= $tempo ?></li>
                      <?php if (!empty($pessoa['vestimentas'])): ?>
                        <li class="list-group-item"><strong>Vestimentas:</strong> <?= htmlspecialchars($pessoa['vestimentas']) ?></li>
                      <?php endif; ?>
                      <?php if (!empty($pessoa['caracteristicas'])): ?>
                        <li class="list-group-item"><strong>Características:</strong> <?= htmlspecialchars($pessoa['caracteristicas']) ?></li>
                      <?php endif; ?>
                    </ul>
                    <h3 class="h5 mt-4">Contatos</h3>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                      <a href="tel:190" class="btn btn-danger">
                        <i class="bi bi-telephone"></i> Polícia (190)
                      </a>
                      <a href="tel:181" class="btn btn-outline-danger">
                        <i class="bi bi-megaphone"></i> Disque Denúncia (181)
                      </a>
                      <?php if (!empty($pessoa['contatoFamilia'])): ?>
                        <a href="tel:<?= preg_replace('/\D/', '', $pessoa['contatoFamilia']) ?>" class="btn btn-outline-primary">
                          <i class="bi bi-person-lines-fill"></i> Família
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php if (!empty($pessoa['ultimoLocalVisto'])): ?>
                <div class="modal-footer">
                  <div class="w-100">
                    <h3 class="h5">Último Local Visto</h3>
                    <p><?= htmlspecialchars($pessoa['ultimoLocalVisto']) ?></p>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    
    <!-- Paginação -->
    <?php if ($totalPaginas > 1): ?>
    <nav aria-label="Navegação de páginas" class="mt-5">
      <div class="d-flex justify-content-center overflow-auto px-2">
        <ul class="pagination d-flex flex-wrap justify-content-center gap-2 mb-0">
          <?php if ($paginaAtual > 1): ?>
            <li class="page-item">
              <a class="page-link rounded-pill border-0 shadow-sm px-4 py-2 text-dark" 
                 href="?<?= http_build_query(array_merge($_GET, ['pagina' => $paginaAtual - 1])) ?>">
                <i class="bi bi-chevron-left"></i>
              </a>
            </li>
          <?php endif; ?>
          
          <?php 
          $intervalo = 2;
          $inicio = max(1, $paginaAtual - $intervalo);
          $fim = min($totalPaginas, $paginaAtual + $intervalo);
          
          if ($paginaAtual <= $intervalo) $fim = min(5, $totalPaginas);
          if ($paginaAtual > $totalPaginas - $intervalo) $inicio = max(1, $totalPaginas - 4);
          
          if ($inicio > 1): ?>
            <li class="page-item">
              <a class="page-link rounded-pill border-0 px-4 py-2 bg-light text-dark" 
                 href="?<?= http_build_query(array_merge($_GET, ['pagina' => 1])) ?>">
                1
              </a>
            </li>
            <li class="page-item disabled">
              <span class="page-link border-0 bg-transparent">...</span>
            </li>
          <?php endif; ?>
          
          <?php for ($i = $inicio; $i <= $fim; $i++): ?>
            <li class="page-item <?= $i === $paginaAtual ? 'active' : '' ?>">
              <a class="page-link rounded-pill border-0 px-4 py-2 <?= $i === $paginaAtual ? 'bg-dark text-white shadow-sm' : 'bg-light text-dark' ?>" 
                 href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>">
                <?= $i ?>
              </a>
            </li>
          <?php endfor; ?>
          
          <?php if ($fim < $totalPaginas): ?>
            <li class="page-item disabled">
              <span class="page-link border-0 bg-transparent">...</span>
            </li>
            <li class="page-item">
              <a class="page-link rounded-pill border-0 px-4 py-2 bg-light text-dark" 
                 href="?<?= http_build_query(array_merge($_GET, ['pagina' => $totalPaginas])) ?>">
                <?= $totalPaginas ?>
              </a>
            </li>
          <?php endif; ?>
          
          <?php if ($paginaAtual < $totalPaginas): ?>
            <li class="page-item">
              <a class="page-link rounded-pill border-0 shadow-sm px-4 py-2 text-dark" 
                 href="?<?= http_build_query(array_merge($_GET, ['pagina' => $paginaAtual + 1])) ?>">
                <i class="bi bi-chevron-right"></i>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
    <?php endif; ?>
  </section>
</main>
</body>
</html>
<?php include 'includes/footer.php'; ?>