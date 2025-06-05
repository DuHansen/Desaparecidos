<<?php
session_start();

// Verifica se a sessão contém os dados corretos do usuário
if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || 
    !isset($_SESSION['user']['nome']) || !isset($_SESSION['user']['email'])) {
  
  // Se não, redireciona para login e destrói a sessão
  session_destroy();
  header('Location: ../index.php');
  exit;
}

$user = $_SESSION['user']; // ✅ Agora é seguro acessar nome e email

require_once './includes/db.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM desaparecidos ORDER BY data_desaparecimento DESC");
    $stmt->execute();
    $desaparecidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<?php include 'includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo - Desaparecidos SC</title>
  
  <!-- Bootstrap LOCAL -->
  <link href="../assets/css/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
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
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active text-white" href="home.php">
                <i class="bi bi-house-door"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="cadastrar.php">
                <i class="bi bi-person-plus"></i> Cadastrar Desaparecido
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="relatorios.php">
                <i class="bi bi-file-earmark-text"></i> Relatórios
              </a>
            </li>
          </ul>
          
          <div class="mt-4 p-3">
           <div class="user-info bg-secondary text-white p-3 rounded">
  <h6 class="mb-3"><?= htmlspecialchars($user['nome']) ?></h6>
  <p class="small mb-1"><?= htmlspecialchars($user['email']) ?></p>
  <p class="small mb-0">Último acesso: <?= date('d/m/Y H:i') ?></p>
</div>

            <form action="../api/logout.php" method="post" class="mt-3">
              <button type="submit" class="btn btn-outline-light w-100">
                <i class="bi bi-box-arrow-right"></i> Sair
              </button>
            </form>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Pessoas Desaparecidas</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <a href="cadastrar.php" class="btn btn-primary">
              <i class="bi bi-plus-circle"></i> Novo Cadastro
            </a>
          </div>
        </div>

        <!-- Cards de Desaparecidos -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <?php if (empty($desaparecidos)): ?>
            <div class="col-12">
              <div class="alert alert-info">
                Nenhum desaparecido cadastrado ainda.
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($desaparecidos as $desaparecido): ?>
              <div class="col">
                <div class="card h-100">
                  <span class="badge bg-<?= $desaparecido['status'] == 'encontrado' ? 'success' : 'danger' ?> status-badge">
                    <?= ucfirst($desaparecido['status']) ?>
                  </span>
                  <img src="../uploads/<?= htmlspecialchars($desaparecido['foto']) ?>" 
                       class="card-img-top" 
                       alt="Foto de <?= htmlspecialchars($desaparecido['nome']) ?>"
                       onerror="this.src='../assets/img/placeholder.jpg'">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($desaparecido['nome']) ?></h5>
                    <p class="card-text">
                      <strong>Idade:</strong> <?= calcularIdade($desaparecido['data_nascimento']) ?><br>
                      <strong>Desaparecido em:</strong> <?= date('d/m/Y', strtotime($desaparecido['data_desaparecimento'])) ?><br>
                      <strong>Cidade:</strong> <?= htmlspecialchars($desaparecido['cidade']) ?>
                    </p>
                  </div>
                  <div class="card-footer bg-transparent">
                    <a href="detalhes.php?id=<?= $desaparecido['id'] ?>" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-eye"></i> Ver Detalhes
                    </a>
                    <a href="editar.php?id=<?= $desaparecido['id'] ?>" class="btn btn-outline-secondary btn-sm">
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
    // Script para alternar o sidebar em mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('collapsed');
    });
  </script>
</body>
</html>