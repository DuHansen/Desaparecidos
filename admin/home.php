
<?php include 'includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo - Desaparecidos SC</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  
  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .card-img-top {
      height: 250px;
      object-fit: cover;
    }
    .card {
      transition: transform 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .user-info {
      background-color: #f8f9fa;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }
    .status-badge {
      position: absolute;
      top: 10px;
      right: 10px;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
        <div class="position-sticky pt-3">
          <div class="mt-4 p-3">
            <div class="user-info bg-secondary text-white p-3 rounded">
              <p class="small mb-0">Último acesso: <?= date('d/m/Y H:i') ?></p>
            </div>
          </div>
        </div>
      </nav>

      <!-- Conteúdo principal -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Reportações de Pessoas Desaparecidas</h1>
        </div>

        <!-- Cards de desaparecidos -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <?php if (empty($desaparecidos)): ?>
            <div class="col-12">
              <div class="alert alert-info">
                Nenhum desaparecido cadastrado ainda.
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($desaparecidos as $p): ?>
              <div class="col">
                <div class="card h-100">
                  <span class="badge bg-<?= $p['status'] === 'encontrado' ? 'success' : 'danger' ?> status-badge">
                    <?= ucfirst($p['status']) ?>
                  </span>
                  <img src="../uploads/<?= htmlspecialchars($p['foto']) ?>" 
                       class="card-img-top" 
                       alt="Foto de <?= htmlspecialchars($p['nome']) ?>"
                       onerror="this.src='../assets/img/placeholder.jpg'">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($p['nome']) ?></h5>
                    <p class="card-text">
                      <strong>Idade:</strong> <?= calcularIdade($p['data_nascimento']) ?><br>
                      <strong>Desaparecido em:</strong> <?= date('d/m/Y', strtotime($p['data_desaparecimento'])) ?><br>
                      <strong>Cidade:</strong> <?= htmlspecialchars($p['cidade']) ?>
                    </p>
                  </div>
                  <div class="card-footer bg-transparent">
                    <a href="detalhes.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-eye"></i> Ver Detalhes
                    </a>
                    <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-outline-secondary btn-sm">
                      <i class="bi bi-pencil"></i> Editar
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Função para calcular idade -->
  <?php
  function calcularIdade($dataNascimento) {
      if (empty($dataNascimento)) return 'Não informada';
      $hoje = new DateTime();
      $nascimento = new DateTime($dataNascimento);
      $idade = $nascimento->diff($hoje)->y;
      return $idade . ' anos';
  }
  ?>

  <script>
    // Alternância do menu lateral em mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('collapsed');
    });
  </script>
</body>
</html>
