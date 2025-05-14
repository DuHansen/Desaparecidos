<?php
namespace App\Controllers;

use App\Models\Desaparecido;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DesaparecidoController
{
    public function criar(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $model = new Desaparecido();
        $model->criar($data);

        $response->getBody()->write(json_encode(['msg' => 'Desaparecido cadastrado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listarTodos(Request $request, Response $response): Response
    {
        $model = new Desaparecido();
        $dados = $model->listarTodos();

        $response->getBody()->write(json_encode($dados));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarPorId(Request $request, Response $response, $args): Response
    {
        $model = new Desaparecido();
        $dado = $model->buscarPorId($args['id']);

        if (!$dado) {
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json')->write(json_encode(['erro' => 'Registro não encontrado']));
        }

        $response->getBody()->write(json_encode($dado));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function atualizar(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $model = new Desaparecido();
        $model->atualizar($args['id'], $data);

        $response->getBody()->write(json_encode(['msg' => 'Desaparecido atualizado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deletar(Request $request, Response $response, $args): Response
    {
        $model = new Desaparecido();
        $model->deletar($args['id']);

        $response->getBody()->write(json_encode(['msg' => 'Desaparecido removido']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
