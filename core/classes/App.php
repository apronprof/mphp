<?php

namespace Core\Classes;

class App
{
    public static function findController($data, $request){

        // Getting names of a controller and a method
        $path = explode('@', $data);
        $method = $path[1];
        $controller = '\App\Controllers\\'.$path[0];


        $app = new $controller();
        return $app->$method($request);
        //self::emit($app->$method($request));
    }

    private static function emit($response){
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        echo $response->getBody();

    }
}
