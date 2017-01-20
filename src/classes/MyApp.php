<?php
/**
 * Created by PhpStorm.
 * User: evis
 * Date: 1/20/17
 * Time: 3:41 PM
 */

namespace Evis;

use Exception;
use Throwable;
use Slim\App;
use Slim\Container;
use Slim\Http\Headers;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Evis\Http\ArrayResponse;


class MyApp extends App{
    private $container;
    public function __construct($container = [])
    {
        $this->container = new Container([
            'response' => function ($c) {
                $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
                $response = new ArrayResponse(200, $headers);

                return $response->withProtocolVersion($c->get('settings')['httpVersion']);
            }
        ]);

        parent::__construct($this->container);
    }

    /**
     * Process a request
     *
     * Override the process method of Slim framework so that we can write
     * our custom array to the response body as Json.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     *
     * @throws Exception
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function process(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Ensure basePath is set
        $router = $this->container->get('router');
        if (is_callable([$request->getUri(), 'getBasePath']) && is_callable([$router, 'setBasePath'])) {
            $router->setBasePath($request->getUri()->getBasePath());
        }

        // Dispatch the Router first if the setting for this is on
        if ($this->container->get('settings')['determineRouteBeforeAppMiddleware'] === true) {
            // Dispatch router (note: you won't be able to alter routes after this)
            $request = $this->dispatchRouterAndPrepareRoute($request, $router);
        }

        // Traverse middleware stack
        try {
            $response = $this->callMiddlewareStack($request, $response);
        } catch (Exception $e) {
            $response = $this->handleException($e, $request, $response);
        } catch (Throwable $e) {
            $response = $this->handlePhpError($e, $request, $response);
        }

        //Write the data array as Json to the response body
        if($response instanceof ArrayResponse){
            $response = $response->withJson($response->getArrayData());
        }

        $response = $this->finalize($response);

        return $response;
    }
}