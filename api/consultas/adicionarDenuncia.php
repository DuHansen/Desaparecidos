<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
        throw new Exception('Método inválido ou sem dados recebidos.');
    }

    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erro ao enviar a foto da denúncia.');
    }

    if (!isset($_FILES['denunciante_documento']) || $_FILES['denunciante_documento']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erro ao enviar o documento do denunciante.');
    }

    $foto = file_get_contents($_FILES['foto']['tmp_name']);
    $documento = file_get_contents($_FILES['denunciante_documento']['tmp_name']);
    $nome = $_POST['nome'] ?? 'Pessoa Não Identificada';

    $dados = [
        'users_id' => 1,
        'titulo' => 'Desaparecimento de ' . $nome,
        'descricao' => $_POST['circunstancias'] ?? 'Sem descrição',
        'nome_completo' => $nome,
        'apelido' => $_POST['apelido'] ?? null,
        'idade' => $_POST['idade'] ?? null,
        'sexo' => $_POST['sexo'] ?? null,
        'data_nascimento' => $_POST['nascimento'] ?? null,
        'data_desaparecimento' => $_POST['desaparecido_em'] ?? null,
        'cidade' => $_POST['cidade'] ?? null,
        'ultimo_local' => $_POST['ultimo_local'] ?? null,
        'circunstancias' => $_POST['circunstancias'] ?? null,
        'altura' => $_POST['altura'] ?? null,
        'peso' => $_POST['peso'] ?? null,
        'cor_pele' => $_POST['cor_pele'] ?? null,
        'vestimentas' => $_POST['vestimentas'] ?? null,
        'caracteristicas' => $_POST['caracteristicas'] ?? null,
        'telefone_contato' => $_POST['contato'] ?? null,
        'email' => $_POST['email'] ?? null,
        'data_criacao' => date('Y-m-d H:i:s'),
        'status' => 'PENDENTE',
        'denunciante_nome' => $_POST['denunciante_nome'] ?? null,
        'denunciante_cpf' => $_POST['denunciante_cpf'] ?? null,
        'denunciante_email' => $_POST['denunciante_email'] ?? null
    ];

    $camposObrigatorios = ['nome_completo', 'idade', 'sexo', 'data_desaparecimento', 'cidade', 'ultimo_local', 'telefone_contato', 'denunciante_nome', 'denunciante_cpf', 'denunciante_email'];
    foreach ($camposObrigatorios as $campo) {
        if (empty($dados[$campo])) {
            throw new Exception("Campo obrigatório ausente: $campo");
        }
    }

    $sql = "INSERT INTO denuncia (
        users_id, titulo, descricao, nome_completo, apelido, idade, sexo, data_nascimento,
        data_desaparecimento, cidade, ultimo_local, circunstancias,
        altura, peso, cor_pele, vestimentas, caracteristicas,
        telefone_contato, email, data_criacao, status, foto,
        denunciante_nome, denunciante_cpf, denunciante_email, denunciante_documento
    ) VALUES (
        :users_id, :titulo, :descricao, :nome_completo, :apelido, :idade, :sexo, :data_nascimento,
        :data_desaparecimento, :cidade, :ultimo_local, :circunstancias,
        :altura, :peso, :cor_pele, :vestimentas, :caracteristicas,
        :telefone_contato, :email, :data_criacao, :status, :foto,
        :denunciante_nome, :denunciante_cpf, :denunciante_email, :denunciante_documento
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
    $stmt->bindParam(':denunciante_documento', $documento, PDO::PARAM_LOB);

    foreach ($dados as $key => $value) {
        if (in_array($key, ['foto', 'denunciante_documento'])) continue;
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Denúncia registrada com sucesso.']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
