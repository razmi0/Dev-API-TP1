<?php


namespace Core;

use HTTP\{Request, Response};
use Middleware\Middleware;
use Model\Schema\Schema;
use Exception;


/**
 * 
 * class Endpoint
 * 
 * This class is the base class for all the endpoints.
 * It is a abstract class that will be extended by all the endpoints.
 * Endpoint enforce the implementation of the following handlers and 
 * provide the necessary properties to the child class.
 * 
 * 
 * @property Response $response
 * @property Request $request
 * @property Schema $schema
 * @property Middleware $middleware
 * 
 * @method handleMiddleware()
 * @method handleRequest()
 * @method handleResponse(mixed $payload)
 */
abstract class Endpoint
{

    protected Response $response;
    protected Request $request;
    protected Schema $schema;
    protected Middleware $middleware;

    public function __construct(Request $request, Response $response, Middleware $middleware, Schema $schema)
    {

        /**
         * as we can't inforce child constants implementation with traits or interfaces in PHP 8.2.4 without hacky workarounds
         * we will throw an exception if the child class does not define the ENDPOINT_METHOD constant
         * @see https://stackoverflow.com/questions/10368620/abstract-constants-in-php-force-a-child-class-to-define-a-constant
         */
        if (!defined('static::ENDPOINT_METHOD'))
            throw new Exception("ENDPOINT_METHOD constant is not defined in the child class");


        $this->request = $request;
        $this->response = $response;
        $this->schema = $schema;
        $this->middleware = $middleware;
    }

    /**
     * ✅ Because all endpoints have identical checks, I centralized them here ( DRY principle)
     */
    abstract protected function handleMiddleware(): void;

    /**
     * 🧠 handleRequest contain the core logic of the endpoint, the business logic
     */
    abstract protected function handleRequest(): array;

    /**
     * 📡 handleResponse is responsible for sending the response back to the client
     */
    abstract protected function handleResponse(mixed $data): void;
}
