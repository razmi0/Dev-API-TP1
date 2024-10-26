<?php

namespace Middleware;


require_once '../../Autoloader.php';

use HTTP\Request;
use HTTP\Error;
use Model\Schema\Schema;

use Exception;

/**
 * 
 * class Middleware
 * 
 * @property Request $request
 * @property array $middlewares An associative array of middlewares and their arguments
 * 
 */
class Middleware
{

    private ?Request $request = null;
    private ?array $middlewares = null;

    public function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function run(): void
    {
        if (!$this->request) {
            throw new Error("Request object is not set");
        }

        try {
            foreach ($this->middlewares as $middleware => $arguments) {
                if (method_exists($this, $middleware)) {
                    $this->$middleware($arguments);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Middleware 1
     * */
    protected function checkAllowedMethods(): void
    {
        // We check if the method is authorized
        // If the method is not authorized, we throw an error and send it to the client
        if ($this->request->is_methods_not_authorized()) {
            $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $this->request->getAuthorizedMethods());
            throw Error::HTTP405($error_message, [], $this->request->getEndpoint());
        }
    }

    /**
     * Middleware 2
     * */
    protected function checkValidJson(): void
    {
        // We check if the client data is a valid JSON
        // If the data is not a valid JSON, we throw an error with the JSON error message and send it to the client
        if (!$this->request->getIsValidJson()) {
            $error_message = $this->request->getJsonErrorMsg();
            throw Error::HTTP400("Données invalides " . $error_message, [], $this->request->getEndpoint());
        }
    }

    /**
     * Middleware 3
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
                throw Error::HTTP400("Données invalides", $data_parsed->getErrorResults(), $this->request->getEndpoint());
            }
        }
    }
}
