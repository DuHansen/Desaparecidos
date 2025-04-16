<?php
function getDBConnection() {
  $host = 'localhost';
  $db   = 'dbdesaparecidos';       // Nome do banco de dados
  $user = 'root';           // Usuário do banco
  $pass = '';               // Senha do banco
  $charset = 'utf8mb4';

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
  $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try {
      return new PDO($dsn, $user, $pass, $options);
  } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['error' => 'Erro de conexão: ' . $e->getMessage()]);
      exit;
  }
}
