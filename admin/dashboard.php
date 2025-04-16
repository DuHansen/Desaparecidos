<?php
// Iniciar a sessão caso precise usar mais tarde
session_start();

// Definindo usuário de exemplo
$user = [
    'nome' => 'Carlos Silva',
    'avatar' => 'https://via.placeholder.com/40' // URL de exemplo para o avatar
];

$desaparecidos = [
    [
        "nome" => "CACILDA APARECIDA DE FREITAS RIBEIRO",
        "idade" => "76",
        "desaparecidoEm" => "2023-03-15",
        "cidade" => "São Paulo"
    ],
    [
        "nome" => "JOSÉ ANTONIO DOS SANTOS",
        "idade" => "54",
        "desaparecidoEm" => "2023-04-20",
        "cidade" => "Rio de Janeiro"
    ],
    [
        "nome" => "MARIA FERNANDA ALMEIDA",
        "idade" => "32",
        "desaparecidoEm" => "2023-02-05",
        "cidade" => "Belo Horizonte"
    ]
];
?>
<?php include '../includes/headerUser.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Adicionando o link para o Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJxwJ6J6/5sM8JmjOTR6ggs98EKg8ks18/Fy5XX52tvQVp2ORAtQ3PbFcoyY" crossorigin="anonymous">
</head>
<body class="bg-light">
  <!-- HOME / TABELA DE DESAPARECIDOS -->
  <div class="container">
    <h2 class="mb-4">Desaparecidos</h2>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Idade</th>
          <th>Desaparecido Em</th>
          <th>Cidade</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($desaparecidos as $desaparecido): ?>
        <tr>
          <td><?php echo $desaparecido['nome']; ?></td>
          <td><?php echo $desaparecido['idade']; ?></td>
          <td><?php echo date('d/m/Y', strtotime($desaparecido['desaparecidoEm'])); ?></td>
          <td><?php echo $desaparecido['cidade']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Adicionando o script do Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb5Rz2Tw7zG4VtFfDiyClCs3y4O8l56jJXt9r5ttH7h6v9E6+" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0V7yWq2RW58R6gk6Q0/pvc6vqyyg4ZmVVdxa7H7XPTOFzoc2" crossorigin="anonymous"></script>
</body>
</html>
