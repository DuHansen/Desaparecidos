<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
  header('Location: ../index.php');
  exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Área Restrita</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    .navbar-custom {
      background-color: #0d6efd;
    }
    .dropdown-menu {
      min-width: 200px;
    }
    .user-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="home.php">
        <i class="bi bi-shield-lock me-2"></i>Sistema Restrito
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="perfil.php"><i class="bi bi-person me-1"></i>Perfil</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-gear me-1"></i>Configurações
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="preferencia.php"><i class="bi bi-sliders me-2"></i>Preferências</a></li>
              <li><a class="dropdown-item" href="seguranca.php"><i class="bi bi-shield me-2"></i>Segurança</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="ajuda.php"><i class="bi bi-question-circle me-2"></i>Ajuda</a></li>
            </ul>
          </li>
        </ul>
        
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <img src="<?= isset($user['avatar']) ? $user['avatar'] : 'https://via.placeholder.com/32' ?>" class="user-avatar me-1" alt="Avatar">
              <?= htmlspecialchars($user['nome'] ?? $user['email']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <div class="d-flex align-items-center px-3 py-2">
                  <img src="<?= isset($user['avatar']) ? $user['avatar'] : 'https://via.placeholder.com/64' ?>" class="user-avatar me-2" style="width: 48px; height: 48px;">
                  <div>
                    <h6 class="mb-0"><?= htmlspecialchars($user['nome'] ?? $user['email']) ?></h6>
                    <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                  </div>
                </div>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
              <li><a class="dropdown-item" href="configuracoes.php"><i class="bi bi-gear me-2"></i>Configurações</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="../api/logout.php" method="post">
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                  </button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>