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
        $this->get[$url] = [$controller, $middlewares];
    }

    public function post($url, $controller, $middlewares = []){
        $this->post[$url] = [$controller, $middlewares];
    }

    public function put($url, $controller, $middlewares = []){
        $this->put[$url] = [$controller, $middlewares];
    }

    public function delete($url, $controller, $middlewares = []){
        $this->delete[$url] = [$controller, $middlewares];
    }

    public function patch($url, $controller, $middlewares = []){
        $this->patch[$url] = [$controller, $middlewares];
    }

    public function group($prefix, $function){
        $function($prefix);
    }

    public function match($url_arr){
        $method = $this->method;
        $set_urls = $this->$method;

        foreach($set_urls as $url => $controller){
            $get_arr = explode('/', $url);
            $params = [];

            for($i=0;$i<count($get_arr);$i++){
                if(!isset($url_arr[$i])){
                    continue(2);
                }
                if(count($get_arr) != count($url_arr)){
                    continue(2);
                }

                if($get_arr[$i] == $url_arr[$i]){
                    continue;
                }
                else if(isset(explode('{', $get_arr[$i])[1])){
                    $params[trim($get_arr[$i], '{}')] = $url_arr[$i];
                    continue;
                }
                else{
                    continue(2);
                }
            }

            return ['controller' => $controller, 'params' => $params];
        }
        if(isset($this->_404)){
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
