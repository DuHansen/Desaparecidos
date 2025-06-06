<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define o fuso horário para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Conexão com o banco
require_once '../includes/db.php';

// Lê o corpo da requisição
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validação da estrutura da requisição
if (!is_array($data) || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Requisição malformada']);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

// Verifica se os campos não estão vazios
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Email e senha são obrigatórios.']);
    exit;
}

// Consulta o usuário pelo e-mail
try {
   $stmt = $pdo->prepare("SELECT id, name AS nome, email, password, role FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica a senha
    if ($user && password_verify($password, $user['password'])) {
        // Protege contra session fixation
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'role' => $user['role'],
            'login_time' => (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s')
        ];


        // Retorna sucesso
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'email' => $user['email']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Credenciais inválidas']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados']);
}
