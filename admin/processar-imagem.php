<?php
// Verificar se a imagem foi enviada
if (isset($_FILES['image'])) {
    $image = $_FILES['image'];

    // Verificar se não houve erro no envio
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Obter o caminho temporário do arquivo
        $tmp_name = $image['tmp_name'];
        $name = basename($image['name']);

        // Validar o tipo de arquivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $allowedTypes)) {
            // Processar a imagem (exemplo de salvar no servidor)
            move_uploaded_file($tmp_name, "uploads/$name");

            // Aqui você pode adicionar o código para fazer o processamento facial (por exemplo, usando face-api.js ou uma outra biblioteca)
            echo json_encode(['message' => 'Imagem recebida com sucesso']);
        } else {
            echo json_encode(['error' => 'Tipo de imagem inválido.']);
        }
    } else {
        echo json_encode(['error' => 'Erro no envio da imagem.']);
    }
} else {
    echo json_encode(['error' => 'Nenhuma imagem foi enviada.']);
}
?>
