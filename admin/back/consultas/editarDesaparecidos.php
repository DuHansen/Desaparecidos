<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

try {
    $dados = json_decode(file_get_contents("php://input"), true);

    // Log para depurar (remova depois!)
    file_put_contents('log_debug.txt', print_r($dados, true));

    if (empty($dados['id']) || empty($dados['nome']) || empty($dados['cidade']) || empty($dados['data_desaparecimento'])) {
        throw new Exception('Campos obrigatórios ausentes.');
    }

    $stmt = $pdo->prepare("
        UPDATE desaparecidos SET
            nome_completo = :nome,
            cidade = :cidade,
            data_desaparecimento = :data_desaparecimento,
            foto = :foto
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $dados['id'],
        ':nome' => $dados['nome'],
        ':cidade' => $dados['cidade'],
        ':data_desaparecimento' => $dados['data_desaparecimento'],
        ':foto' => $dados['foto'] ?? null
    ]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Nenhum registro foi atualizado. Verifique o ID: {$dados['id']}");
    }

    echo json_encode(['sucesso' => 'Registro atualizado com sucesso!']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['erro' => $e->getMessage()]);
}
