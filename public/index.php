<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Evis\Middleware\MiddlewareTest1;
use \Evis\Middleware\MiddlewareTest2;

require '../vendor/autoload.php';

$app = new \Evis\MyApp;

$container = $app->getContainer();

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
})->add(new MiddlewareTest1())
    ->add(new MiddlewareTest2());

$app->run();