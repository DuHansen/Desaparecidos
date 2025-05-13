<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
  // Redireciona para o login
  header('Location: ../index.html');
  exit;
}

$user = $_SESSION['user'];
?>
<?php include 'includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Configurações</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
</head>
<body>
  <h2>Configurações<?= htmlspecialchars($user['email']) ?></h2>
  <p>ID do usuário: <?= $user['id'] ?></p>
  
  <form action="../api/logout.php" method="post">
    <button type="submit">Sair</button>
  </form>
</body>
</html>
