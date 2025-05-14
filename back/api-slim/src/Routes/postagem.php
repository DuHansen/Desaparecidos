<?php
use Slim\App;
use App\Controllers\PostagemController;

return function (App $app) {
    $app->get('/postagens', [PostagemController::class, 'listarTodos']);    // listar
    $app->get('/postagens/{id}', [PostagemController::class, 'buscarPorId']); // buscar por id
    $app->post('/postagens', [PostagemController::class, 'criar']);         // criar
    $app->put('/postagens/{id}', [PostagemController::class, 'atualizar']); // atualizar
    $app->delete('/postagens/{id}', [PostagemController::class, 'deletar']); // deletar
};
