<?php include 'includes/header.php'; ?>
<?php
// Iniciar a sessão caso precise usar mais tarde
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/style.css"> 
</head>
<body>

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6 col-lg-4">
      <div class="card login-card border-0 p-4">
        <div class="card-body">
          <h2 class="text-center text-primary mb-4">Login</h2>
          <form id="loginForm">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" placeholder="Digite seu email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Senha</label>
              <input type="password" class="form-control" id="password" placeholder="Digite sua senha" required>
            </div>
            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
            <div id="error-message" class="alert alert-danger mt-3 d-none">
              Erro ao logar, verifique suas credenciais.
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const form = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      try {
        const response = await fetch('api/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok && data.success) {
          window.location.href = 'admin/home.php';
        } else {
          errorMessage.textContent = data.error || 'Erro ao logar';
          errorMessage.classList.remove('d-none');
        }
      } catch (error) {
        errorMessage.textContent = 'Erro de conexão com o servidor.';
        errorMessage.classList.remove('d-none');
      }
    });
  </script>
</body>
</html>
<?php include 'includes/footer.php'; ?>