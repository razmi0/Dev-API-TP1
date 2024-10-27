<?php

namespace Middleware;


require_once '../../Autoloader.php';

use Closure;
use HTTP\Request;
use HTTP\Error;
use Model\Schema\Schema;

use Exception;

/**
 * 
 * class Middleware
 * 
 * @property Request $request
 * @property callable $handler
 * 
 */
class Middleware
{

    private ?Request $request = null;
    /**
     * @var callable $handler The callback provided by the consumer to handle the request
     */
    private $handler = null;

    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function handleMiddleware(): void
    {
        try {

            // We create a new closure where the handler provided by the consumer is binded 
            // to the $this object (new Middleware) and a new scope (Middleware)
            $binded_handler = Closure::bind(
                /**
                 * The handler provided by the consumer
                 */
                $this->handler,
                /**
                 * The newThis object giving access to the request object and the middleware object in the handler
                 */
                $this,
                /**
                 * The new scope of the handler
                 */
                self::class,
            );

            // We execute the binded handler
            // All middleware return void so no need to grab a return value
            $binded_handler();
        } catch (Error) {
            throw Error::HTTP500("Erreur interne du serveur", [], "Middleware");
        }
    }

    /**
     * 
     * Middleware
     * 
     * @return void
     * @throws Error
     * 
     * */
    protected function checkAllowedMethods(): void
    {
        // We check if the method is authorized
        // If the method is not authorized, we throw an error and send it to the client
        if ($this->request->is_methods_not_authorized()) {
            $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $this->request->getAuthorizedMethods());
            throw Error::HTTP405($error_message, [], "checkAllowedMethods");
        }
    }

    /**
     * Middleware
     * 
     * @return void
     * @throws Error
     * 
     * */
    protected function checkValidJson(): void
    {
        // We check if the client data is a valid JSON
        // If the data is not a valid JSON, we throw an error with the JSON error message and send it to the client
        if (!$this->request->getIsValidJson()) {
            $error_message = $this->request->getJsonErrorMsg();
            throw Error::HTTP400("Données invalides " . $error_message, [], "checkValidJson");
        }
    }

    /**
     * Middleware
     * 
     * @return void
     * @throws Error
     * 
     * */
    protected function checkExpectedData(Schema $schema): void
    {
        // We check if a schema is defined
        // If a schema is defined, we parse the client data with the schema
        // If the client data is invalid against the schema, we throw an error and send it to the client
        if ($schema) {
            $client_data = $this->request->getDecodedBody();
            $data_parsed = $schema->safeParse($client_data);
            if ($data_parsed->getHasError()) {
                throw Error::HTTP400("Données invalides", $data_parsed->getErrorResults(), "checkExpectedData");
            }
        }
    }
}
