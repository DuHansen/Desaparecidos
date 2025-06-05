<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define o fuso horário para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Usuário fixo para testes
$loginPredefinido = [
  'email' => 'admin@teste.com',
  'password' => '123456',
  'nome' => 'Administrador'
];

// Lê e decodifica JSON recebido
$data = json_decode(file_get_contents("php://input"), true);

// Validação básica
if (!$data || !isset($data['email']) || !isset($data['password'])) {
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => 'Requisição malformada']);
  exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

// Verifica login
if ($email === $loginPredefinido['email'] && $password === $loginPredefinido['password']) {
  
  // Cria o objeto DateTime com timezone de Brasília
  $agora = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

  // Salva na sessão
  $_SESSION['user'] = [
    'id' => 1,
    'nome' => $loginPredefinido['nome'],
    'email' => $loginPredefinido['email'],
    'login_time' => (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s')

  ];

  echo json_encode(['success' => true]);
} else {
  http_response_code(401);
  echo json_encode(['success' => false, 'error' => 'Credenciais inválidas']);
}
