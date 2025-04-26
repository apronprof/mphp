<?php

namespace Core\Classes;

class Request
{
    public $urlParams;
    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public readonly array $get,
        public readonly array $post,
        public readonly array $headers,
        public readonly array $cookies,
        public readonly ?array $session)
    {
             
    }


    public static function fromGlobals(): self {
        return new self(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_GET,
            $_POST,
            getallheaders(),
            $_COOKIE,
            $_SESSION ?? null,
        );
    }

    public function addUrlParams($params){
        $this->urlParams = $params;
    }
}

?>
