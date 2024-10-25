<?php

namespace Controller;

require_once '../../Autoloader.php';

use Closure;
use HTTP\Error;
use HTTP\Request;
use HTTP\Response;
use Controller\ControllerInterface;
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
 * @property Schema $schema
 * @property callable $handler
 * 
 * @method __construct(Request $request, Schema $schema, Response $response) Dependency injection
 * @method handleRequest(callable $handler): void
 * 
 */
class Controller implements ControllerInterface
{
    protected ?Error $error = null;
    protected ?Response $response = null;
    protected ?Request $request = null;
    protected ?Schema $schema = null;
    /**
     * @var callable
     */
    protected $handler;

    public function __construct(Request $request, Response $response, Schema $schema = null, callable $handler, ...$args)
    {
        // ...args is a placeholder for future use ( chaining middlewares, handlers ? etc )
        try {
            $this->request = $request;
            $this->response = $response;
            $this->error = new Error();
            $this->schema = $schema;
            $this->handler = $handler;

            // We get the authorized methods from the request
            $methods = $request->getAuthorizedMethods();

            // We set the authorized methods for the response header
            $response->setMethods($methods);

            // We set the endpoint for debugging purpose
            $endpoint = $request->getEndpoint();
            $this->error->setLocation($endpoint);
            $this->response->setLocation($endpoint);

            /**
             * Middleware 1
             * */
            // We check if the method is authorized
            // If the method is not authorized, we throw an error and send it to the client
            if ($request->is_methods_not_authorized()) {
                $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $methods);
                throw $this->error->HTTP405($error_message, [], $endpoint);
            }

            /**
             * Middleware 2
             * */
            // We check if the client data is a valid JSON
            // If the data is not a valid JSON, we throw an error with the JSON error message and send it to the client
            if (!$request->getIsValidJson()) {
                $error_message = $request->getJsonErrorMsg();
                throw $this->error->HTTP400("Données invalides " . $error_message, [], $endpoint);
            }

            /**
             * Middleware 3
             * */
            // We check if a schema is defined
            // If a schema is defined, we parse the client data with the schema
            // If the client data is invalid against the schema, we throw an error and send it to the client
            if ($schema) {
                $client_data = $request->getDecodedBody();
                $data_parsed = $schema->safeParse($client_data);
                if ($data_parsed->getHasError()) {
                    throw $this->error->HTTP400("Données invalides", $data_parsed->getErrorResults(), $endpoint);
                }
            }

            // We handle the request with the handler provided by the Controller consumer
            $this->handleRequest();
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }

    private function handleRequest(): void
    {
        // We provide and bind all the necessary data to a new Closure (a callback, an instance and a scope)
        $binded_handler = Closure::bind(
            /**
             * the callback provided by the Controller consumer
             */
            $this->handler,
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
