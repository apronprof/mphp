<?php

namespace Core\Classes;

class Router
{
    private 
        $get = [],
        $post = [],
        $put = [],
        $delete = [],
        $patch = [],
        $method = 'get',
        $prefix = '',
        $_404 = null;

    public function __construct(){

    }

    public function setMethod($method){
        $method = strtolower($method);
        if(isset($this->$method)){
            $this->method = $method;
        }
    }

    public function get($url, $controller, $middlewares = []){
        $this->get[$this->prefix . $url] = [$controller, $middlewares];
    }

    public function post($url, $controller, $middlewares = []){
        $this->post[$this->prefix . $url] = [$controller, $middlewares];
    }

    public function put($url, $controller, $middlewares = []){
        $this->put[$this->prefix . $url] = [$controller, $middlewares];
    }

    public function delete($url, $controller, $middlewares = []){
        $this->delete[$this->prefix . $url] = [$controller, $middlewares];
    }

    public function patch($url, $controller, $middlewares = []){
        $this->patch[$this->prefix . $url] = [$controller, $middlewares];
    }

    public function group($prefix, $function){
        $prevPrefix = $this->prefix;
        $this->prefix = $prefix;
        $function($this);
        $this->prefix = $prevPrefix;
    }

    
    public function match($url_arr)
    {
        $method = $this->method;
        $set_urls = $this->$method ?? [];

        // Преобразуем URL в строку, убирая ведущие и конечные слеши
        $requestUri = empty($url_arr) || $url_arr === [''] ? '/' : '/' . implode('/', array_filter($url_arr, fn($v) => $v !== ''));
        $requestUri = rtrim($requestUri, '/');

        foreach ($set_urls as $pattern => $controller) {
            // Нормализуем шаблон маршрута
            $cleanPattern = $pattern === '' || $pattern === '/' ? '/' : '/' . trim($pattern, '/');
            
            // Преобразуем {parameter} в регулярное выражение
            $regex = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([^/]+)', $cleanPattern);
            $regex = '#^' . ($cleanPattern === '/' ? '' : $regex) . '$#';

            // Проверяем совпадение
            if (preg_match($regex, $requestUri, $matches)) {
                $params = [];
                if (count($matches) > 1) {
                    // Извлекаем имена параметров
                    preg_match_all('#\{([a-zA-Z0-9_]+)\}#', $pattern, $paramNames);
                    $paramNames = $paramNames[1];
                    array_shift($matches); // Удаляем полное совпадение

                    // Сопоставляем имена и значения
                    foreach ($paramNames as $index => $name) {
                        $params[$name] = $matches[$index];
                    }
                }

                return ['controller' => $controller, 'params' => $params];
            }
        }

        if (isset($this->_404)) {
            return ['controller' => [$this->_404, []], 'params' => []];
        }

        return ['controller' => ['Controller@_404', []], 'params' => []];
    } 

    public function _404($controller){
        $this->_404 = $controller;
    }

    public function get404(){
        return $this->_404;
    }

}
