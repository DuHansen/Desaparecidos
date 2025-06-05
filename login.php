<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acesso ao Sistema - Desaparecidos SC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-dark text-white d-flex flex-column min-vh-100">

  <?php include 'includes/header.php'; ?>

  <main class="flex-grow-1 d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card bg-secondary border-light shadow-lg">
            <div class="card-body p-4">
              <div class="text-center mb-4">
                <h4 class="fw-bold">Acesso Restrito</h4>
                <p class="text-light small">Sistema de Gerenciamento de Desaparecidos</p>
              </div>

              <form id="loginForm" novalidate>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" class="form-control bg-dark text-white border-secondary" placeholder="seu@email.com" required>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Senha</label>
                  <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" class="form-control bg-dark text-white border-secondary" placeholder="••••••••" required>
                  </div>
                </div>

                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="remember">
                  <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>

                <div class="d-grid mb-3">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Entrar
                  </button>
                </div>

                <div class="text-end mb-3">
                  <a href="forgot-password.php" class="link-light small">Esqueceu a senha?</a>
                </div>

                <div id="error-message" class="alert alert-danger d-none" role="alert">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i>
                  <span>Erro ao logar, verifique suas credenciais.</span>
                </div>
              </form>

              <div class="text-center mt-4">
                <p class="small text-light">Precisa de ajuda?</p>
                <a href="#" class="text-decoration-none text-light small me-3"><i class="bi bi-question-circle me-1"></i>Suporte</a>
                <a href="#" class="text-decoration-none text-light small me-3"><i class="bi bi-file-earmark-text me-1"></i>Termos</a>
                <a href="#" class="text-decoration-none text-light small"><i class="bi bi-shield-lock me-1"></i>Privacidade</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
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
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById('email');
    const rememberCheckbox = document.getElementById('remember');

    // Se já existe email salvo, preenche
    const savedEmail = localStorage.getItem('rememberedEmail');
    if (savedEmail) {
      emailInput.value = savedEmail;
      rememberCheckbox.checked = true;
    }

    // Ao enviar o formulário
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }

      const email = emailInput.value;
      const password = document.getElementById('password').value;

      // Salva ou limpa
      if (rememberCheckbox.checked) {
        localStorage.setItem('rememberedEmail', email);
      } else {
        localStorage.removeItem('rememberedEmail');
      }

      // Continue o login normalmente...
      // Aqui você pode inserir sua lógica de autenticação com fetch.
    });
  });
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
