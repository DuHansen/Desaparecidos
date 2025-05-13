<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Dados de login pré-definidos (exemplo)
$loginPredefinido = [
  'email' => 'admin@teste.com',
  'password' => '123456' // em produção, nunca armazene senhas em texto plano
];

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

// Verificação com login pré-definido
if ($email === $loginPredefinido['email'] && $password === $loginPredefinido['password']) {
  $_SESSION['user'] = [
    'id' => 1, // você pode definir um ID qualquer aqui
    'email' => $email
  ];
  error_log("Login bem-sucedido com credenciais pré-definidas");
  echo json_encode(['success' => true]);
  exit;
} else {
  error_log("Credenciais inválidas para email: $email");
  http_response_code(401);
  echo json_encode(['error' => 'Credenciais inválidas']);
  exit;
}
