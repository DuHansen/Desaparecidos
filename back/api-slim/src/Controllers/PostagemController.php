<?php
namespace App\Controllers;

use App\Models\Postagem;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostagemController
{
    public function criar(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $postagem = new Postagem();
        $postagem->criar($data);

        $response->getBody()->write(json_encode(['msg' => 'Postagem criada']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listarTodos(Request $request, Response $response): Response
    {
        $postagem = new Postagem();
        $dados = $postagem->listarTodos();

        $response->getBody()->write(json_encode($dados));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarPorId(Request $request, Response $response, $args): Response
    {
        $postagem = new Postagem();
        $dado = $postagem->buscarPorId($args['id']);

        if (!$dado) {
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json')->write(json_encode(['erro' => 'Postagem não encontrada']));
        }

        $response->getBody()->write(json_encode($dado));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function atualizar(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $postagem = new Postagem();
        $postagem->atualizar($args['id'], $data);

        $response->getBody()->write(json_encode(['msg' => 'Postagem atualizada']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deletar(Request $request, Response $response, $args): Response
    {
        $postagem = new Postagem();
        $postagem->deletar($args['id']);

        $response->getBody()->write(json_encode(['msg' => 'Postagem deletada']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
