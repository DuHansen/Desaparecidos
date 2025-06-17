<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recebe JSON com os dados do Laravel
$input = file_get_contents('php://input');
$user = json_decode($input, true);

if (isset($user['id'], $user['nome'], $user['email'], $user['role'])) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'nome' => $user['nome'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
