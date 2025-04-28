<?php

use \Core\Classes\Router as Router;

$router = new Router;

// Routes

$router->get('', 'IndexController@index');
$router->get('auth/{id}', 'IndexController@user', [new App\Middlewares\AuthMiddleware()]);

$router->group('user', function($router){
    $router->get('/{id}', 'IndexController@index');
});

$router->_404('IndexController@_404');

// End
return $router;
