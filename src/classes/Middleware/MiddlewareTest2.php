<?php
/**
 * Author: Evis Bregu
 * Date: 01/20/2017
 * Time: 16:02 PM
 */
namespace Evis\Middleware;

class MiddlewareTest2
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

        $data = array(2=>array('name' => 'EVIS', 'age' => 35));

        $newResponse = $response->appendArrayData($data);

        return $newResponse;
    }
}