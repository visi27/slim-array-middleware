<?php
/**
 * Author: Evis Bregu
 * Date: 01/20/2017
 * Time: 16:00 PM
 */
namespace Evis\Middleware;

class MiddlewareTest1
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        $data = array(1=>array('name' => 'Bob', 'age' => 40));

        $newResponse = $response->appendArrayData($data);

        return $newResponse;
    }
}