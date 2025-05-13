<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Opcional -->
</head>
<body>

<header class="bg-black text-white py-3">
    <div class="container">
        <h1 class="text-center">Onde esta você?</h1>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="./index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="./regulamento.php">Regulamento</a></li>
                        <li class="nav-item"><a class="nav-link" href="./contato.php">Contato</a></li>
                        <li class="nav-item"><a class="nav-link" href="./login.php">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const currentPath = window.location.pathname;
    
    // Função para definir o link ativo
    function setActiveLink() {
        navLinks.forEach(link => {
            // Remove a classe active de todos os links
            link.classList.remove('active');
            
            // Verifica se o href do link corresponde ao caminho atual
            const linkPath = new URL(link.href).pathname;
            if (linkPath === currentPath) {
                link.classList.add('active');
            }
        });
    }
    
    // Define o link ativo quando a página carrega
    setActiveLink();
    
    // Adiciona o evento de clique para cada link
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Atualiza o link ativo imediatamente (antes da navegação)
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Opcional: se você quiser garantir que o estado persista após a navegação
            // você pode armazenar o link ativo no sessionStorage
            sessionStorage.setItem('activeLink', this.getAttribute('href'));
        });
    });
    
    // Verifica se há um link ativo armazenado no sessionStorage
    const storedActiveLink = sessionStorage.getItem('activeLink');
    if (storedActiveLink) {
        const matchingLink = Array.from(navLinks).find(link => 
            link.getAttribute('href') === storedActiveLink
        );
        if (matchingLink) {
            navLinks.forEach(l => l.classList.remove('active'));
            matchingLink.classList.add('active');
        }
    }
});
</script>
</body>
</html>