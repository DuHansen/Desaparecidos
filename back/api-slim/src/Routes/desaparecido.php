<?php
use Slim\App;
use App\Controllers\DesaparecidoController;

return function (App $app) {
    $app->get('/desaparecidos', [DesaparecidoController::class, 'listarTodos']);   // listar
    $app->get('/desaparecidos/{id}', [DesaparecidoController::class, 'buscarPorId']); // buscar por id
    $app->post('/desaparecidos', [DesaparecidoController::class, 'criar']);        // criar
    $app->put('/desaparecidos/{id}', [DesaparecidoController::class, 'atualizar']); // atualizar
    $app->delete('/desaparecidos/{id}', [DesaparecidoController::class, 'deletar']); // deletar
};
