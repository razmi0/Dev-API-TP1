<?php

namespace Controller;

require_once '../../Autoloader.php';

use Closure;
use HTTP\Error;
use HTTP\Request;
use HTTP\Response;
use Middleware\ProductMiddleware as Middleware;
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
 * 
 * @method void setMiddleware(Middleware $middleware)
 * @method void setHandler(callable $handler)
 * @method void handleBusinessLogic(callable $handler)
 * @method void run()
 * 
 */
class ProductController
{
    protected ?Response $response = null;
    protected ?Request $request = null;
    protected ?Middleware $middleware = null;

    public function __construct(Request $request, Response $response)
    {

        try {
            $this->request = $request;
            $this->response = $response;

            // We get the authorized methods from the request and set them to the response
            $methods = $request->getAuthorizedMethods();
            $response->setMethods($methods);
        } catch (\Throwable $th) {
            throw Error::HTTP500("Erreur interne", [], "Controller");
        }
    }

    public function setMiddleware(Middleware $middleware): void
    {
        $this->middleware = $middleware;
        $this->middleware->setRequest($this->request);
    }

    /**
     * Handle the business logic of the controller providing a context of execution to the handler with a scope and an object (closure)
     * Store the response data in the response object and send it to the client
     */
    public function run(callable $handler): void
    {
        // We check if the handler is set and callable and if the middleware is set
        if (!$handler || !is_callable($handler) || !$this->middleware) {
            throw Error::HTTP500("Erreur interne", [], "Controller");
        }

        // We launch the middleware logic
        $this->middleware->handleMiddleware();

        // We launch the business logic
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
}
