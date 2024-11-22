<?php

namespace API\Controllers;

use Dotenv\Dotenv;
use HTTP\{Request, Response};
use Middleware\Middleware;
use Exception;
use Middleware\Validators\Validator;
use OpenApi\Annotations as OA;

const PROJECT_ROOT = __DIR__ . "/../";

require_once PROJECT_ROOT . "vendor/autoload.php";


// Load the environment variables
$dotenv = Dotenv::createImmutable(PROJECT_ROOT, '.env.local');

$dotenv->load();

/**
 * 
 * class ControllerEndpoint
 * 
 * This class is the base class for all the endpoints.
 * It is a abstract class that will be extended by all the endpoints.
 * Endpoint enforce the implementation of the following handlers and 
 * provide the necessary properties to the child class.
 * 
 * 
 * @property Response $response
 * @property Request $request
 * @property Validator $validator
 * @property Middleware $middleware
 * 
 * @method handleMiddleware()
 * @method handleRequest()
 * @method handleResponse(mixed $payload)
 */

/**
 * @OA\Info(
 *     version="1.0",
 *     description="CRUD on a simple product entity",
 *     title="Product API",
 *     @OA\Contact(
 *         name="Thomas Cuesta",
 *         email="thomas.cuesta@my-digital-school.org",
 *         url="https://github.com/razmi0/Dev-API-TP1/tree/release/version-final"
 *     )
 * )
 */
abstract class ControllerEndpoint
{
    protected Response $response;
    protected Request $request;
    protected Validator $validator;
    protected Middleware $middleware;

    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
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
        $this->validator = $validator;
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
