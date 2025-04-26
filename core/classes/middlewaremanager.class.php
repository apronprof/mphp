<?php

namespace Core\Classes;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MiddlewareManager implements RequestHandlerInterface
{
    private array $middlewareQueue;
    private $finalHandler;

    public function __construct(array $middlewareQueue, callable $finalHandler)
    {
        $this->middlewareQueue = $middlewareQueue;
        $this->finalHandler = $finalHandler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middlewareQueue)) {
            $handler = $this->finalHandler;
            return $handler($request);
        }

        $middleware = array_shift($this->middlewareQueue);

        return $middleware->process($request, $this);
    }
}

?>
