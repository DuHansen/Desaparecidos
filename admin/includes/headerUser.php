<?php
// Inicializa sessão, caso ainda não esteja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Usuários permitidos por perfil
$permitidos = ['admin', 'user'];

// Verifica se usuário está autenticado e tem papel autorizado
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $permitidos)) {
    header('Location: ../index.php');
    exit;
}

// Redireciona para HTTPS apenas em ambiente de produção
if ($_SERVER['HTTP_HOST'] !== 'localhost' && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
    $https_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $https_url", true, 301);
    exit();
}

// Dados do usuário logado (sessão já validada)
$usuario = [
    'nome' => $_SESSION['user']['nome'],
    'email' => $_SESSION['user']['email'],
    'id' => $_SESSION['user']['id'],
    'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg', // avatar temporário fixo
    'perfil' => $_SESSION['user']['role']
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Desaparecidos</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }
        .navbar-custom {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-link-custom {
            color: white !important;
            transition: all 0.3s;
            margin: 0 5px;
            font-weight: 500;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--secondary-color) !important;
            transform: translateY(-2px);
        }
        .dropdown-menu-custom {
            background-color: var(--primary-color);
            border: none;
        }
        .dropdown-item-custom {
            color: black !important;
        }
        .dropdown-item-custom:hover {
            background-color: var(--secondary-color);
        }
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary-color);
            transition: all 0.3s;
        }
        .user-avatar:hover {
            transform: scale(1.1);
        }
        .search-box {
            position: relative;
            width: 250px;
        }
        .search-input {
            background-color: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding-left: 35px;
        }
        .search-input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.6);
        }
        .feature-icon {
            font-size: 1.2rem;
            margin-right: 8px;
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="home.php">
                <i class="fas fa-search-location feature-icon"></i>
                <span class="ms-2">Sistema Desaparecidos</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Menu Principal -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="home.php">
                            <i class="fas fa-home feature-icon"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="analise-dados.php">
                            <i class="fas fa-chart-bar feature-icon"></i> Análise de Dados
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="inteligenciaDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-brain feature-icon"></i> Inteligência Artificial
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="inteligenciaDropdown">
                            <li><a class="dropdown-item dropdown-item-custom" href="reconhecimento-facial.php">
                                <i class="fas fa-eye feature-icon"></i> Reconhecimento Facial
                            </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="biometria.php">
                                <i class="fas fa-fingerprint feature-icon"></i> Biometria
                            </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="cruzamento-dados.php">
                                <i class="fas fa-project-diagram feature-icon"></i> Cruzamento de Dados
                            </a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Barra de Pesquisa -->
                <div class="search-box me-3">
                    <i class="fas fa-search search-icon"></i>
                    <input class="form-control search-input" type="search" placeholder="Pesquisar desaparecidos..." id="pesquisaInput">
                </div>

                <!-- Menu do Usuário -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?php echo $usuario['avatar']; ?>" class="user-avatar me-2">
                            <span class="text-white"><?php echo $usuario['nome']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item dropdown-item-custom" href="perfil.php">
                                <i class="fas fa-user-circle me-2"></i> Meu Perfil
                            </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="configuracoes.php">
                                <i class="fas fa-cog me-2"></i> Configurações
                            </a></li>
                            <li><hr class="dropdown-divider bg-secondary"></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="suporte.php">
                                <i class="fas fa-headset me-2"></i> Suporte
                                <span class="badge bg-danger badge-notification">3</span>
                            </a></li>
                           <li>
                            <a class="dropdown-item dropdown-item-custom" href="#" onclick="apiLogout(); return false;">
                                <i class="fas fa-sign-out-alt me-2"></i> Sair
                            </a>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="notificacoes.php">
                            <i class="fas fa-bell text-white"></i>
                            <span class="badge bg-danger badge-notification">5</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Scripts -->
<script>
  const inputBusca = document.getElementById('pesquisaInput');
  let timeout = null;

  inputBusca.addEventListener('input', function () {
    clearTimeout(timeout);
    const valor = this.value.trim();

    // Começa a buscar com 2+ letras
    if (valor.length >= 2) {
      timeout = setTimeout(() => {
        window.location.href = `home.php?q=${encodeURIComponent(valor)}`;
      }, 700); // tempo para evitar redirecionar a cada letra
    }
  });
</script>
<script>
async function apiLogout() {
  try {
    // Garante CSRF cookie antes de logout
    await fetch('http://localhost:8000/sanctum/csrf-cookie', { credentials: 'include' });

    const xsrfToken = getCookie('XSRF-TOKEN');

    const response = await fetch('http://localhost:8000/api/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-XSRF-TOKEN': xsrfToken
      },
      credentials: 'include'
    });

    // ⚠️ Certifique-se que o Laravel realmente retorna JSON
    const data = await response.json();
    console.log(data);
    if (response.ok && data.success) {
      // Remove sessão local se estiver usando PHP puro
      await fetch('back/logout.php'); // se você criou essa rota

      // Redireciona normalmente
      window.location.href = '../index.php';
    } else {
      alert('Erro ao fazer logout. Tente novamente.');
    }
  } catch (error) {
    console.error("Erro ao desconectar:", error);
    alert("Falha ao se desconectar.");
  }
}

// Função para pegar cookie (se já não tiver em outro lugar do projeto)
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}
</script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/custom.js"></script>
</body>
</html>
