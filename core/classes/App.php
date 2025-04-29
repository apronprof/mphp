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
    }
    
}
