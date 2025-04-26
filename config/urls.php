<?php

use \Core\Classes\Router as Router;

$router = new Router;

// Routes

$router->get('', 'IndexController@index');
$router->get('user/{id}', 'IndexController@user', [new App\Middlewares\AuthMiddleware()]);

$router->_404('IndexController@_404');

// End
return $router;
