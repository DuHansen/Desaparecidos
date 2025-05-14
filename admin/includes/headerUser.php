<?php
// Iniciar sessão

if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    $https_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $https_url", true, 301);
    exit();
}

// Dados do usuário (simulados)
$usuario = [
    'nome' => 'Eduardo',
    'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
    'perfil' => 'admin'
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Desaparecidos</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
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
                <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
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
                            <a class="nav-link nav-link-custom active" href="dashboard.php">
                                <i class="fas fa-home feature-icon"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="analise-dados.php">
                                <i class="fas fa-chart-bar feature-icon"></i>
                                Análise de Dados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="cadastro.php">
                                <i class="fas fa-user-plus feature-icon"></i>
                                Cadastrar Desaparecido
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="inteligenciaDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-brain feature-icon"></i>
                                Inteligência Artificial
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
                        <input class="form-control search-input" type="search" placeholder="Pesquisar desaparecidos...">
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
                                <li><a class="dropdown-item dropdown-item-custom" href="/desaparecidos/api/logout.php">
    <i class="fas fa-sign-out-alt me-2"></i> Sair
</a></li>
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
    <!-- Custom JS -->
    <script>
        // Ativar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        
        // Barra de pesquisa funcional
        document.querySelector('.search-input').addEventListener('keyup', function(e) {
            if(e.key === 'Enter') {
                const termo = this.value.trim();
                if(termo) {
                    window.location.href = `busca.php?q=${encodeURIComponent(termo)}`;
                }
            }
        });
    </script>
    <!-- Bootstrap JS Local -->
<script src="/assets/js/bootstrap.bundle.min.js"></script>

<!-- Seus scripts personalizados -->
<script src="/assets/js/custom.js"></script>