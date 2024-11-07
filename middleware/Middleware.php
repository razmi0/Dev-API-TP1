<?php

namespace Middleware;


require_once '../../vendor/autoload.php';

use HTTP\Error;
use HTTP\Request;
use Model\Schema\Schema;

/**
 * 
 * class Middleware
 * 
 * This class has all the middleware used in the API.
 * You call use<name> and pass the parameters to add a middleware to the middleware stack.
 * You call runMiddleware to run all the middleware in the stack.
 * 
 */
class Middleware
{

    private ?Request $request = null;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }



    /**
     * 
     * Middleware
     * 
     * @return void
     * 
     * */
    public function checkAllowedMethods(array $allowedMethods): void
    {
        // We check if the method is authorized
        // If the method is not authorized, we return an error with the allowed methods to the consumer
        if (!in_array($this->request->getRequestMethod(), $allowedMethods)) {
            $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $allowedMethods);
            Error::HTTP405($error_message, []);
        }
    }

    /**
     * Middleware
     * 
     * @return void
     * 
     * */
    public function checkValidJson(): void
    {
        // We check if the client data is a valid JSON
        // If the data is not a valid JSON, we throw an error with the JSON error message and send it to the client
        if (!$this->request->getIsValidJson()) {
            $error_message = $this->request->getJsonErrorMsg();
            Error::HTTP400("Données invalides " . $error_message, []);
        }
    }

    /**
     * Middleware
     * 
     * @return void
     * 
     * */
    public function checkExpectedData(Schema $schema): void
    {
        // We check if a schema is defined
        // If a schema is defined, we parse the client data with the schema
        // If the client data is invalid against the schema, we throw an error and send it to the client
        if ($schema) {
            $client_data = $this->request->getDecodedBody();
            $data_parsed = $schema->safeParse($client_data);
            if ($data_parsed->getHasError()) {
                Error::HTTP400("Données invalides", $data_parsed->getErrorResults());
            }
        }
    }
}
