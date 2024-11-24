<?php

namespace API\Controllers;

use Dotenv\Dotenv;
use HTTP\Request;
use HTTP\Response;
use Middleware\Middleware;
use Exception;
use Middleware\Validators\Validator;

const PROJECT_ROOT = __DIR__ . '/../';

require_once PROJECT_ROOT . 'vendor/autoload.php';

// Load the environment variables
$dotenv = Dotenv::createImmutable(PROJECT_ROOT, '.env.local');
$dotenv->load();

/**
 * Class AbstractController
 * 
 * This class is the base class for all the endpoints.
 * It is an abstract class that will be extended by all the endpoints and controllers.
 * AbstractController enforce the implementation of the following handlers and 
 * provide the necessary properties to the child class.
 * 
 * @param Request $request
 * @param Response $response
 * @param Middleware $middleware
 * @param Validator $validator
 */
abstract class AbstractController
{
    public function __construct(
        protected Request $request,
        protected Response $response,
        protected Middleware $middleware,
        protected Validator $validator
    ) {
        // Throw an exception if the child class does not define the ENDPOINT_METHOD constant
        if (!defined('static::ENDPOINT_METHOD')) {
            throw new Exception("ENDPOINT_METHOD constant is not defined in the child class");
        }
    }

    /**
     * Centralized middleware handling for all endpoints (DRY principle)
     */
    abstract protected function handleMiddleware(): void;

    /**
     * Core logic of the endpoint, the business logic
     */
    abstract protected function handleRequest(): array;

    /**
     * Responsible for sending the response back to the client
     */
    abstract protected function handleResponse(mixed $data): void;

    /**
     * This method is called by the router to handle the request
     */
    public function handle(): void
    {
        $this->handleMiddleware();
        $data = $this->handleRequest();
        $this->handleResponse($data);
    }
}
