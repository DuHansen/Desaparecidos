<?php
use Slim\App;
use App\Controllers\UserController;

return function (App $app) {
    $app->get('/usuarios', [UserController::class, 'index']);          // listar todos
    $app->get('/usuarios/{id}', [UserController::class, 'show']);      // buscar por id
    $app->post('/usuarios', [UserController::class, 'criar']);         // criar novo
    $app->put('/usuarios/{id}', [UserController::class, 'atualizar']); // atualizar
    $app->delete('/usuarios/{id}', [UserController::class, 'deletar']); // deletar
    $app->post('/login', [UserController::class, 'login']);            // login
};
