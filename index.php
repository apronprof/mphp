<?php

define('ROOT', __DIR__.'/');
define('CONFIG', ROOT.'config/');
define('APP', ROOT.'app/');
define('PUB', ROOT.'public/');
define('DB', ROOT.'db/');
define('CORE', ROOT.'core/');
define('CLASSES', CORE.'classes/');
define('ML', __DIR__.'/ml/');

define('METHOD', $_SERVER['REQUEST_METHOD']);

// Require functions
require('vendor/autoload.php');
require(CORE.'url.function.php');


use \Core\Classes\Url as URL;
use \Core\Classes\App as App;
use \Core\Classes\DB as DB;
use \Core\Classes\Debug as Debug;
use \Core\Classes\MiddlewareManager as MiddlewareManager;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
error_reporting(E_ALL);
    ini_set('display_errors', 1);


// Require config
$config['app'] = require(CONFIG.'app.php');
define('DEBUG', $config['app']['debug']);


// PSR-7 creating Request

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

// Теперь Request будет сделан из суперглобальных массивов
$request = $creator->fromGlobals();

$customPath = $_GET['url'] ?? '/';
$request = $request->withUri($request->getUri()->withPath("/" . ltrim($customPath, '/')));

$url = new Url(ltrim($request->getUri()->getPath(), '/'));

// Debugging

if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
else{
    error_reporting(0);
    ini_set('display_errors', 0);
}


// Route parsing 
$router = require(CONFIG.'urls.php');

$router->setMethod($request->getMethod());

$matched = $router->match($url->getUrl());

foreach($matched['params'] as $key => $value){
    $request = $request->withAttribute($key, $value);
}

//$request = Request::fromGlobals();
//$request->addUrlParams($matched['params']);





// DB connection
$config['db'] = require(CONFIG.'db.php');
$db = new DB($config['db']);



// Middlewares
session_start();

$middlewares = array_merge(require_once(CONFIG . 'middlewares.php'), $matched['controller'][1]);


//$_SESSION['user'] = '';

$finalHandler = function (Psr\Http\Message\ServerRequestInterface $request) use ($matched) {
    return App::findController($matched['controller'][0], $request);
};

$middlewareManager = new MiddlewareManager($middlewares, $finalHandler);
$response = $middlewareManager->handle($request);

$emitter = new SapiEmitter();
$emitter->emit($response);


// Closing the db connection
DB::close();
