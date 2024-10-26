<?php

namespace Controller;

require_once '../../Autoloader.php';

use Closure;
use HTTP\Error;
use HTTP\Request;
use HTTP\Response;
use Middleware\Middleware;
use Model\Schema\Schema;

/**
 *  _____ _______ _______ _______ _______ _______ _______ _______
 * |                                                             |
 * |                     |
 * |_____________________________________________________________|
 * 
 */

/**
 * 
 * class Controller
 * 
 * @property Request $request
 * @property Response $response
 * @property Middleware $middleware
 * @property callable $handler
 * 
 * @method __construct(Request $request, Schema $schema, Response $response) Dependency injection
 * @method handleRequest(callable $handler): void
 * 
 */
class Controller
{
    protected ?Response $response = null;
    protected ?Request $request = null;
    /**
     * @var callable $handler The callback provided by the consumer to handle the request
     */
    protected $handler;

    public function __construct(Request $request, Response $response, Middleware $middleware, callable $handler)
    {

        try {
            $this->request = $request;
            $this->response = $response;
            $this->handler = $handler;

            // We set the request object to the middleware
            $middleware->setRequest($this->request);

            // We run the middleware
            $middleware->run();

            // We get the authorized methods from the request
            $methods = $request->getAuthorizedMethods();

            // We set the authorized methods for the response header
            $response->setMethods($methods);
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }

    private function handleRequest(callable $handler): void
    {
        // We provide and bind all the necessary data to a new Closure (a callback, an instance and a scope)
        $binded_handler = Closure::bind(
            /**
             * the callback provided by the Controller consumer
             */
            $handler,
            /**
             * $this is the new this of the closure $handler.
             * This makes the controller's properties available in the closure ( $this->request, $this->response, $this->error, $this->schema ) 
             */
            $this,
            /**
             * Controller is the scope of the closure $handler
             */
            self::class
        );

        // We retrieve the response data from the handler execution (ex : [ "products" => $products ])
        $response_data = $binded_handler();

        // We send the response data to the client using the response object
        $this->response
            ->setData($response_data)
            ->sendAndDie();
    }

    public function run(): void
    {
        try {
            $this->handleRequest($this->handler);
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }
}
