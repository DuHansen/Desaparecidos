<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

try {
    $dados = json_decode(file_get_contents("php://input"), true);

    if (empty($dados['id'])) {
        throw new Exception('ID obrigatório.');
    }

    $stmt = $pdo->prepare("DELETE FROM desaparecidos WHERE id = :id");
    $stmt->execute([':id' => $dados['id']]);

    echo json_encode(['sucesso' => 'Registro excluído com sucesso!']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['erro' => $e->getMessage()]);
}
