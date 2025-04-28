<?php

namespace Core\Classes;

use Nyholm\Psr7\Factory\Psr17Factory;

class BaseController
{
    protected function config($type){
        return require(CONFIG.$type.'.php');
    }

    protected function response($status = 200, $body = '', $type='text/html'){
        $psr17Factory = new Psr17Factory();
        $response = $psr17Factory->createResponse($status);
        $body = $psr17Factory->createStream($body);

        return $response
            ->withBody($body)
            ->withHeader('Content-Type', $type);
    }

    protected function json($status = 200, $body = ''){
        return $this->response($status, json_encode($body), 'application/json');
    }

    protected function view($__name, $__params = []){
        $psr17Factory = new Psr17Factory();
        include(CORE . 'assets.function.php');

        ob_start();
        extract($__params);

        require(PUB . "views/$__name.php");

        $html = ob_get_clean();

        // Собираем Response
        $response = $psr17Factory->createResponse(200);
        $body = $psr17Factory->createStream($html);

        return $response
            ->withBody($body)
            ->withHeader('Content-Type', 'text/html');
    }

}
