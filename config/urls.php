<?php

use \Core\Classes\Router as Router;

$router = new Router;

// Routes

$router->get('', 'IndexController@index');
$router->get('predict/{num}', 'IndexController@predict');
$router->get('/getjson', 'IndexController@getJson');

$router->group('/user', function($router){
    $router->get('/{id}', 'IndexController@user');
}, [new App\Middlewares\AuthMiddleware()]);

$router->_404('IndexController@_404');

// End
return $router;
