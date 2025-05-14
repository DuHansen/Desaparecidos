<?php
namespace App\Helpers;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseFormatter
{
    public static function success(Response $response, $data = null, $message = 'Sucesso', $status = 200)
    {
        $payload = [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public static function error(Response $response, $message = 'Erro', $status = 400)
    {
        $payload = [
            'status' => 'error',
            'message' => $message
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
