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
<?php include '../includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Preferencia</title>
   <!-- Bootstrap LOCAL -->
  <link href="assets/css/bootstrap-5.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="assets/css/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
  <h2>Preferencia <?= htmlspecialchars($user['email']) ?></h2>
  <p>ID do usuário: <?= $user['id'] ?></p>
  
  <form action="../api/logout.php" method="post">
    <button type="submit">Sair</button>
  </form>
</body>
</html>
