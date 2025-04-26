<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class HttpMethodMiddleware extends Middleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() != "GET" && $request->getMethod() != "POST") {
            $factory = $this->getResponse();
            return $factory->createResponse(405)
                           ->withBody($factory->createStream('HTTP method not allowed'));
        }

        // Передаем дальше, если всё хорошо
        return $handler->handle($request);
    }
}

?>
