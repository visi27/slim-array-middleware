<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Evis\Middleware\MiddlewareTest1;
use \Evis\Middleware\MiddlewareTest2;
use \Evis\Http\ArrayResponse;
use \Slim\Http\Headers;

require '../../vendor/autoload.php';

$app = new \Slim\App;

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['response'] = function ($container) {
    $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
    $response = new ArrayResponse(200, $headers);

    return $response->withProtocolVersion($container->get('settings')['httpVersion']);
};

$logger = $container->get('logger');

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
})->add(new MiddlewareTest1($logger))
->add(new MiddlewareTest2($logger));
$app->run();