<?php

// Require functions
require('consts.php');
require('vendor/autoload.php');
require(CORE.'url.function.php');


// Connecting to other namespaces
use \Core\Classes\Url as URL;
use \Core\Classes\App as App;
use \Core\Classes\DB as DB;
use \Core\Classes\Debug as Debug;
use \Core\Classes\MiddlewareManager as MiddlewareManager;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;


// Require config
$config['app'] = require(CONFIG.'app.php');
define('DEBUG', $config['app']['debug']);


// Debugging
if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}
else{
    error_reporting(0);
    ini_set('display_errors', 0);
}


// PSR-7 creating Request
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();

$customPath = $_GET['url'] ?? '/';
$request = $request->withUri($request->getUri()->withPath("/" . ltrim($customPath, '/')));


// Additional class for formatting the url
$url = new Url(ltrim($request->getUri()->getPath(), '/'));


// Route parsing 
$router = require(CONFIG.'urls.php');

$router->setMethod($request->getMethod());

$matched = $router->match($url->getUrl());

foreach($matched['params'] as $key => $value){
    $request = $request->withAttribute($key, $value);
}


// DB connection
$config['db'] = require(CONFIG.'db.php');
$db = new DB($config['db']);


// Middlewares
session_start();

$middlewares = array_merge(require_once(CONFIG . 'middlewares.php'), $matched['controller'][1]);

$finalHandler = function (Psr\Http\Message\ServerRequestInterface $request) use ($matched) {
    return App::findController($matched['controller'][0], $request);
};

$middlewareManager = new MiddlewareManager($middlewares, $finalHandler);
$response = $middlewareManager->handle($request);


// Sending HTTP response
$emitter = new SapiEmitter();
$emitter->emit($response);


// Closing the db connection
DB::close();
