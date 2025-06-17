
<?php include 'includes/header.php'; ?>
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
<?php
// Função para calcular idade a partir da data de nascimento
function calcularIdade($dataNascimento) {
    if (empty($dataNascimento)) return '-';
    try {
        $hoje = new DateTime();
        $nascimento = new DateTime($dataNascimento);
        $idade = $nascimento->diff($hoje)->y;
        if ($idade < 1) {
            $meses = $nascimento->diff($hoje)->m;
            return $meses . ' meses';
        }
        return $idade . ' anos';
    } catch (Exception $e) {
        return '-';
    }
}

// Função para calcular tempo desde o desaparecimento
function calcularTempoDesaparecimento($dataDesaparecimento) {
    if (empty($dataDesaparecimento)) return 'tempo desconhecido';
    try {
        $hoje = new DateTime();
        $desaparecidoEm = DateTime::createFromFormat('d/m/Y', $dataDesaparecimento);
        if (!$desaparecidoEm) return 'tempo desconhecido';
        
        $intervalo = $desaparecidoEm->diff($hoje);
        if ($intervalo->y > 0) return $intervalo->y . ' ano' . ($intervalo->y > 1 ? 's' : '');
        if ($intervalo->m > 0) return $intervalo->m . ' mes' . ($intervalo->m > 1 ? 'es' : '');
        if ($intervalo->d >= 7) return floor($intervalo->d / 7) . ' semana' . (floor($intervalo->d / 7) > 1 ? 's' : '');
        if ($intervalo->d > 0) return $intervalo->d . ' dia' . ($intervalo->d > 1 ? 's' : '');
        return 'menos de 24h';
    } catch (Exception $e) {
        return 'tempo desconhecido';
    }
}

// Busca os dados da API com tratamento de erros
try {
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $apiUrl = "$baseUrl/api/desaparecidos.php";
    
    $params = [];
    if (!empty($_GET['filtro'])) {
        $params['filtro'] = htmlspecialchars($_GET['filtro']);
        $params['valor'] = $_GET['filtro'] === 'tempo' ? 
            (htmlspecialchars($_GET['tempo'] ?? '')) : 
            (htmlspecialchars($_GET['valor'] ?? ''));
    }
    
    $url = $apiUrl . (!empty($params) ? '?' . http_build_query($params) : '');
    
    // Usando cURL para melhor controle da requisição
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Apenas para desenvolvimento
    $json = curl_exec($ch);
    
    if (curl_errno($ch)) {
        throw new Exception("Erro na conexão: " . curl_error($ch));
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        throw new Exception("Erro HTTP $httpCode ao acessar a API");
    }
    
    curl_close($ch);
    
    $pessoas = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON inválido recebido da API. Erro: " . json_last_error_msg());
    }
    
    if (!is_array($pessoas)) {
        throw new Exception("Formato de dados inesperado");
    }
    
    $maisRecentes = array_slice($pessoas, 0, 5);
    
} catch (Exception $e) {
    $error_message = $e->getMessage();
    error_log("Erro na API: " . $error_message);
    $pessoas = [];
    $maisRecentes = [];
}

// PAGINAÇÃO SEGURA
$paginaAtual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$porPagina = 20;
$total = count($pessoas);
$totalPaginas = max(1, ceil($total / $porPagina));
$pessoasPaginadas = array_slice($pessoas, ($paginaAtual - 1) * $porPagina, $porPagina);
$intervalo = 2;
$inicio = max(1, $paginaAtual - $intervalo);
$fim = min($totalPaginas, $paginaAtual + $intervalo);
if ($paginaAtual <= $intervalo) $fim = min(5, $totalPaginas);
if ($paginaAtual > $totalPaginas - $intervalo) $inicio = max(1, $totalPaginas - 4);
?>

<main class="py-5">
  <!-- Seção de alerta caso haja erro na API -->
  <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-octagon-fill"></i> Erro ao carregar dados: <?= htmlspecialchars($error_message) ?>
      <?php if (isset($url)): ?>
        <div class="mt-2">
          <small class="text-muted">URL: <?= htmlspecialchars($url) ?></small>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Carrossel com mais recentes -->
  <?php if (!empty($maisRecentes)): ?>
  <section class="container mb-5">
    <h2 class="h4 mb-3 text-muted"><i class="bi bi-clock-history me-2"></i>Casos recentes</h2>
    <div id="carouselDesaparecidos" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
      <div class="carousel-inner rounded">
        <?php foreach ($maisRecentes as $index => $pessoa): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="d-flex justify-content-center p-3 bg-light">
              <div class="card text-center" style="max-width: 400px;">
                <div class="overflow-hidden" style="height: 300px;">
                  <img src="<?= htmlspecialchars($pessoa['foto'] ?? 'assets/img/placeholder.jpg') ?>" 
                       class="w-100 h-100" style="object-fit: cover;" 
                       alt="Foto de <?= htmlspecialchars($pessoa['nome'] ?? 'pessoa desaparecida') ?>"
                       onerror="this.src='assets/img/placeholder.jpg'">
                </div>
                <div class="card-body">
                  <h3 class="card-title h5"><?= htmlspecialchars($pessoa['nome'] ?? 'Nome não informado') ?></h3>
                  <div class="card-text text-start">
                    <p><strong>Idade:</strong> <?= calcularIdade($pessoa['data_nascimento'] ?? null) ?></p>
                    <p><strong>Desaparecido em:</strong> <?= htmlspecialchars($pessoa['desaparecidoEm'] ?? '-') ?></p>
                    <p><strong>Local:</strong> <?= htmlspecialchars($pessoa['cidade'] ?? '-') ?></p>
                    <p class="text-danger"><strong>Há:</strong> <?= calcularTempoDesaparecimento($pessoa['desaparecidoEm'] ?? null) ?></p>
                  </div>
                  <button class="btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#infoModal<?= $index ?>">
                    <i class="bi bi-info-circle"></i> Mais detalhes
                  </button>
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
  <?php endif; ?>

  <!-- Modais para mais informações -->
  <?php foreach ($maisRecentes as $index => $pessoa): ?>
  <div class="modal fade" id="infoModal<?= $index ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h2 class="modal-title h5"><?= htmlspecialchars($pessoa['nome'] ?? 'Nome não informado') ?></h2>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <img src="<?= htmlspecialchars($pessoa['foto'] ?? 'assets/img/placeholder.jpg') ?>" 
                   class="img-fluid rounded mb-3" 
                   alt="Foto de <?= htmlspecialchars($pessoa['nome'] ?? 'pessoa desaparecida') ?>"
                   onerror="this.src='assets/img/placeholder.jpg'">
            </div>
            <div class="col-md-6">
              <h3 class="h5">Informações</h3>
              <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><strong>Idade:</strong> <?= calcularIdade($pessoa['data_nascimento'] ?? null) ?></li>
                <li class="list-group-item"><strong>Desaparecido em:</strong> <?= htmlspecialchars($pessoa['desaparecidoEm'] ?? '-') ?></li>
                <li class="list-group-item"><strong>Local:</strong> <?= htmlspecialchars($pessoa['cidade'] ?? '-') ?></li>
                <li class="list-group-item"><strong>Há:</strong> <?= htmlspecialchars($pessoa['tempoDesaparecimento'] ?? 'tempo desconhecido') ?></li>
                <?php if (!empty($pessoa['vestimentas'])): ?>
                <li class="list-group-item"><strong>Vestimentas:</strong> <?= htmlspecialchars($pessoa['vestimentas']) ?></li>
                <?php endif; ?>
                <?php if (!empty($pessoa['caracteristicas'])): ?>
                <li class="list-group-item"><strong>Características:</strong> <?= htmlspecialchars($pessoa['caracteristicas']) ?></li>
                <?php endif; ?>
              </ul>
              
              <h3 class="h5 mt-4">Contatos</h3>
              <div class="d-flex flex-wrap gap-2">
                <a href="tel:190" class="btn btn-danger">
                  <i class="bi bi-telephone"></i> Polícia (190)
                </a>
                <a href="tel:181" class="btn btn-outline-danger">
                  <i class="bi bi-megaphone"></i> Disque Denúncia (181)
                </a>
                <?php if (!empty($pessoa['contatoFamilia'])): ?>
                <a href="tel:<?= preg_replace('/[^0-9]/', '', $pessoa['contatoFamilia']) ?>" class="btn btn-outline-primary">
                  <i class="bi bi-person-lines-fill"></i> Família
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          
          <?php if (!empty($pessoa['ultimoLocalVisto'])): ?>
          <div class="mt-4">
            <h3 class="h5">Último Local Visto</h3>
            <p><?= htmlspecialchars($pessoa['ultimoLocalVisto']) ?></p>
            <!-- Aqui poderia ser integrado um mapa -->
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="position-relative" style="height: 70vh;">
  <!-- Imagem de fundo -->
  <img src="assets/img/bg.png" alt="Background"
       class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover z-0">

  <!-- Conteúdo centralizado -->
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

  <script>
    window.addEventListener("load", () => {
      const elements = document.querySelectorAll('.slide-left, .slide-right');
      elements.forEach((el, index) => {
        setTimeout(() => el.classList.add('slide-in'), index * 300);
      });
    });
  </script>
  <!-- Filtro de busca -->
  <section class="container bg-light p-4 rounded-3 shadow-sm mb-5">
    <h2 class="h4 mb-4"><i class="bi bi-search me-2"></i>Buscar Desaparecidos</h2>
    <form method="GET" class="needs-validation" novalidate>
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="filtroSelect" class="form-label">Filtrar por</label>
          <select class="form-select" name="filtro" id="filtroSelect" required>
            <option value="" disabled selected>Selecione...</option>
            <option value="nome" <?= ($_GET['filtro'] ?? '') === 'nome' ? 'selected' : '' ?>>Nome</option>
            <option value="cidade" <?= ($_GET['filtro'] ?? '') === 'cidade' ? 'selected' : '' ?>>Cidade</option>
            <option value="idade" <?= ($_GET['filtro'] ?? '') === 'idade' ? 'selected' : '' ?>>Idade</option>
            <option value="tempo" <?= ($_GET['filtro'] ?? '') === 'tempo' ? 'selected' : '' ?>>Tempo de Desaparecimento</option>
          </select>
        </div>
        <div class="col-md-4" id="campoValor">
          <label for="valorFiltro" class="form-label">Valor</label>
          <input type="text" class="form-control" name="valor" id="valorFiltro" 
                 value="<?= htmlspecialchars($_GET['valor'] ?? '') ?>" 
                 <?= ($_GET['filtro'] ?? '') !== 'tempo' ? 'required' : '' ?>>
        </div>
        <div class="col-md-4 <?= ($_GET['filtro'] ?? '') === 'tempo' ? '' : 'd-none' ?>" id="campoTempo">
          <label for="tempoFiltro" class="form-label">Tempo</label>
          <select class="form-select" name="tempo" id="tempoFiltro" <?= ($_GET['filtro'] ?? '') === 'tempo' ? 'required' : '' ?>>
            <option value="" disabled selected>Selecione...</option>
            <option value="1 semana" <?= ($_GET['tempo'] ?? '') === '1 semana' ? 'selected' : '' ?>>1 semana</option>
            <option value="1 mes" <?= ($_GET['tempo'] ?? '') === '1 mes' ? 'selected' : '' ?>>1 mês</option>
            <option value="3 meses" <?= ($_GET['tempo'] ?? '') === '3 meses' ? 'selected' : '' ?>>3 meses</option>
            <option value="6 meses" <?= ($_GET['tempo'] ?? '') === '6 meses' ? 'selected' : '' ?>>6 meses</option>
            <option value="1 ano" <?= ($_GET['tempo'] ?? '') === '1 ano' ? 'selected' : '' ?>>1 ano</option>
            <option value="2 anos+" <?= ($_GET['tempo'] ?? '') === '2 anos+' ? 'selected' : '' ?>>2 anos ou mais</option>
          </select>
        </div>
        <div class="col-md-4">
          <button class="btn btn-primary w-100">
            <i class="bi bi-search me-1"></i> Buscar
          </button>
        </div>
      </div>
    </form>
  </section>

  <!-- Resultados da busca -->
  <section class="container">
    <h2 class="h4 mb-3">
      <i class="bi bi-people-fill me-2"></i>
      <?= !empty($_GET) ? 'Resultados da Busca' : 'Pessoas Desaparecidas' ?>
      <span class="badge bg-danger ms-2"><?= count($pessoas) ?></span>
    </h2>
    
    <?php if (empty($pessoas)): ?>
      <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> Nenhum registro encontrado. Tente ajustar os filtros de busca.
      </div>
    <?php else: ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($pessoasPaginadas as $pessoa): ?>
          <div class="col">
            <div class="card h-100 shadow-sm">
              <div class="overflow-hidden" style="height: 300px;">
                <img src="<?= htmlspecialchars($pessoa['foto'] ?? 'assets/img/placeholder.jpg') ?>" 
                     class="card-img-top w-100 h-100" style="object-fit: cover;" 
                     alt="Foto de <?= htmlspecialchars($pessoa['nome'] ?? 'pessoa desaparecida') ?>"
                     onerror="this.src='assets/img/placeholder.jpg'">
              </div>
              <div class="card-body d-flex flex-column">
                <h3 class="card-title h5"><?= htmlspecialchars($pessoa['nome'] ?? 'Nome não informado') ?></h3>
                <div class="card-text mb-3">
                  <p><strong>Idade:</strong> <?= calcularIdade($pessoa['data_nascimento'] ?? null) ?></p>
                  <p><strong>Desaparecido em:</strong> <?= htmlspecialchars($pessoa['desaparecidoEm'] ?? '-') ?></p>
                  <p><strong>Cidade:</strong> <?= htmlspecialchars($pessoa['cidade'] ?? '-') ?></p>
                 <p class="text-danger"><strong>Há:</strong> <?= calcularTempoDesaparecimento($pessoa['desaparecidoEm'] ?? null) ?></p>
                </div>
                <div class="mt-auto">
                  <button class="btn btn-outline-danger w-100" data-bs-toggle="modal" 
                          data-bs-target="#detalhesModal<?= $pessoa['id'] ?? rand() ?>">
                    <i class="bi bi-info-circle"></i> Detalhes
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Modal para detalhes -->
          <div class="modal fade" id="detalhesModal<?= $pessoa['id'] ?? rand() ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                  <h2 class="modal-title h5"><?= htmlspecialchars($pessoa['nome'] ?? 'Nome não informado') ?></h2>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-6">
                      <img src="<?= htmlspecialchars($pessoa['foto'] ?? 'assets/img/placeholder.jpg') ?>" 
                           class="img-fluid rounded mb-3" 
                           alt="Foto de <?= htmlspecialchars($pessoa['nome'] ?? 'pessoa desaparecida') ?>"
                           onerror="this.src='assets/img/placeholder.jpg'">
                    </div>
                    <div class="col-md-6">
                      <h3 class="h5">Informações</h3>
                      <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item"><strong>Idade:</strong> <?= calcularIdade($pessoa['data_nascimento'] ?? null) ?></li>
                        <li class="list-group-item"><strong>Desaparecido em:</strong> <?= htmlspecialchars($pessoa['desaparecidoEm'] ?? '-') ?></li>
                        <li class="list-group-item"><strong>Local:</strong> <?= htmlspecialchars($pessoa['cidade'] ?? '-') ?></li>
                        <li class="list-group-item"><strong>Há:</strong> <?= htmlspecialchars($pessoa['tempoDesaparecimento'] ?? 'tempo desconhecido') ?></li>
                        <?php if (!empty($pessoa['vestimentas'])): ?>
                        <li class="list-group-item"><strong>Vestimentas:</strong> <?= htmlspecialchars($pessoa['vestimentas']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($pessoa['caracteristicas'])): ?>
                        <li class="list-group-item"><strong>Características:</strong> <?= htmlspecialchars($pessoa['caracteristicas']) ?></li>
                        <?php endif; ?>
                      </ul>
                      
                      <h3 class="h5 mt-4">Contatos</h3>
                      <div class="d-flex flex-wrap gap-2">
                        <a href="tel:190" class="btn btn-danger">
                          <i class="bi bi-telephone"></i> Polícia (190)
                        </a>
                        <a href="tel:181" class="btn btn-outline-danger">
                          <i class="bi bi-megaphone"></i> Disque Denúncia (181)
                        </a>
                        <?php if (!empty($pessoa['contatoFamilia'])): ?>
                        <a href="tel:<?= preg_replace('/[^0-9]/', '', $pessoa['contatoFamilia']) ?>" class="btn btn-outline-primary">
                          <i class="bi bi-person-lines-fill"></i> Família
                        </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  
                  <?php if (!empty($pessoa['ultimoLocalVisto'])): ?>
                  <div class="mt-4">
                    <h3 class="h5">Último Local Visto</h3>
                    <p><?= htmlspecialchars($pessoa['ultimoLocalVisto']) ?></p>
                    <!-- Aqui poderia ser integrado um mapa -->
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
  <!-- Paginação -->
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

      <?php for ($i = $inicio; $i <= $fim; $i++): ?>
        <li class="page-item <?= $i == $paginaAtual ? 'active' : '' ?>">
          <a class="page-link rounded-pill border-0 px-4 py-2 <?= $i == $paginaAtual ? 'bg-dark text-white shadow-sm' : 'bg-light text-dark' ?>"
             href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>">
            <?= $i ?>
          </a>
        </li>
      <?php endfor; ?>

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
</main>
<script>
  // Script para alternar entre campos de filtro
  document.getElementById('filtroSelect').addEventListener('change', function() {
    const campoTempo = document.getElementById('campoTempo');
    const campoValor = document.getElementById('campoValor');
    const valorFiltro = document.getElementById('valorFiltro');
    const tempoFiltro = document.getElementById('tempoFiltro');
    
    if (this.value === 'tempo') {
      campoTempo.classList.remove('d-none');
      campoValor.classList.add('d-none');
      valorFiltro.removeAttribute('required');
      tempoFiltro.setAttribute('required', true);
    } else {
      campoTempo.classList.add('d-none');
      campoValor.classList.remove('d-none');
      valorFiltro.setAttribute('required', true);
      tempoFiltro.removeAttribute('required');
    }
  });

  // Validação do formulário
  (function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        
        form.classList.add('was-validated');
      }, false);
    });
  })();

  // Inicializa o comportamento dos filtros ao carregar a página
  window.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('filtroSelect');
    if (select) {
      const event = new Event('change');
      select.dispatchEvent(event);
    }
  });
</script>

</body>
</html>
<?php include 'includes/footer.php'; ?>