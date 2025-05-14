<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;

class UserController {
    public function criar(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $usuario = new Usuario();
        $usuario->criar($data);
        $response->getBody()->write(json_encode(['msg' => 'Usuário criado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function login(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $usuario = new Usuario();
        $user = $usuario->autenticar($data['email'], $data['senha']);
        if ($user) {
            $response->getBody()->write(json_encode(['msg' => 'Login OK']));
        } else {
            $response->getBody()->write(json_encode(['msg' => 'Credenciais inválidas']));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
