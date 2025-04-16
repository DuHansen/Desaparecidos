<?php
include 'db.php';

$conn = getDBConnection();

if ($conn) {
  echo "Conexão estabelecida com sucesso!";
} else {
  echo "Falha na conexão.";
}
