<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$version = '1';
$page = isset($page) && !empty($page) ? $page : 'default';

if (isset($_SESSION['user'])) {
    // Se a variável de sessão 'user' estiver definida
    $logado = 1;
    $user['id'] = $_SESSION['user']['id'];
    $user['email'] = $_SESSION['user']['email'];
} else {
    // Se a variável de sessão 'user' NÃO estiver definida
    $logado = 0;
    if (!in_array($page, ['login'])) {
        header('Location: logout.php');
        exit;
    }
}
?>
