<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  error_log("Erro ao decodificar JSON: " . file_get_contents("php://input"));
  http_response_code(400);
  echo json_encode(['error' => 'Requisição malformada']);
  exit;
}

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

error_log("Tentativa de login com email: $email");

if (!$email || !$password) {
  error_log("Email ou senha não fornecidos.");
  http_response_code(400);
  echo json_encode(['error' => 'Email e senha obrigatórios']);
  exit;
}

try {
  $conn = getDBConnection();
  $stmt = $conn->prepare("SELECT * FROM dbdesaparecidos.usuario WHERE email = ? LIMIT 1");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    error_log("Usuário encontrado: " . json_encode($user));
  } else {
    error_log("Nenhum usuário encontrado com o email: $email");
  }

  // IMPORTANTE: use password_verify se estiver usando hash
  if ($user && $user['password'] === $password) {
    $_SESSION['user'] = [
      'id' => $user['id'],
      'email' => $user['email']
    ];
    error_log("Login bem-sucedido para o usuário ID: " . $user['id']);
    echo json_encode(['success' => true]);
    exit;
  } else {
    error_log("Senha incorreta para o email: $email");
  }

} catch (Exception $e) {
  error_log("Erro ao tentar logar: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Erro no servidor']);
  exit;
}

http_response_code(401);
echo json_encode(['error' => 'Credenciais inválidas']);
