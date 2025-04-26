<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware extends Middleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->isAuthorized($request)) {
            $response = $this->getResponse();
            return $response->createResponse(401)
                           ->withBody($response->createStream('Unauthorized'));
        }

        // Передаем дальше, если всё хорошо
        return $handler->handle($request);
    }

    private function isAuthorized(ServerRequestInterface $request): bool
    {
        // Тут можно добавить свою проверку авторизации
        return isset($_SESSION['user']);
    }
}

?>
