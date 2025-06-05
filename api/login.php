<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Credenciais fixas para exemplo
$loginPredefinido = [
  'email' => 'admin@teste.com',
  'password' => '123456',
  'nome' => 'Administrador'
];

// Recebe dados JSON do frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validação básica
if (!$data) {
  http_response_code(400);
  echo json_encode(['error' => 'Requisição malformada']);
  exit;
}

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Email e senha obrigatórios']);
  exit;
}

// Verifica login
if ($email === $loginPredefinido['email'] && $password === $loginPredefinido['password']) {
  $_SESSION['user'] = [
    'id' => 1,
    'nome' => $loginPredefinido['nome'],
    'email' => $loginPredefinido['email']
  ];
  echo json_encode(['success' => true]);
  exit;
} else {
  http_response_code(401);
  echo json_encode(['error' => 'Credenciais inválidas']);
  exit;
}
