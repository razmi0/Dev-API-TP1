<?php

namespace API\Controllers;


use API\Endpoint;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};

require_once "../vendor/autoload.php";


final class Signup extends Endpoint
{

    // The only method allowed for this endpoint
    public const ENDPOINT_METHOD = "POST";

    // dependency injection here
    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        /**
         * The parent Endpoint assign the properties (request, response, middleware, schema) as protected properties
         * @see Core/Endpoint.php
         **/
        parent::__construct($request, $response, $middleware, $validator);
    }

    /**
     * 🧠 handleRequest contain the core logic of the endpoint
     */
    public function handleRequest(): array
    {

        print_r($this->request->getDecodedData());

        // user dao



        return [];
    }


    /**
     * ✅ The middleware object will handle all the checks to avoid a bad request
     */
    public function handleMiddleware(): void
    {

        // Check if the request method is allowed (POST only)
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);            // if error return 405 Method Not Allowed

        // Check if the request body contains the expected data 
        // (name type, length, regex ; description type, length, regex ; ect...)
        $this->middleware->checkExpectedData($this->validator);                        // if error, return 400 Bad Request

        // sanitize the data ( all types )
        // get the decoded body from the request, sanitize it
        // and set it back to the request object
        $this->middleware->sanitizeData(                                  // // no error expected
            ["sanitize" => ["html", "integer", "float"]]
        );
    }


    /**
     * 📡 handleResponse is responsible for sending the response back to the client
     */
    public function handleResponse(mixed $data): void {}
}



// ENDPOINT INSTRUCTIONS 👇
// --




/**
 * our request object with all incoming informations (headers, body, method, query string etc...)
 * @see http/Request.php
 */
$request = new Request();





/**
 * our template rules to validate the client data in the request body
 * @see model/schema/Schema.php
 */
$validator = new Validator([
    "username" => [
        "type" => "string",
        "min" => 3,
        "max" => 50,
        "regex" => "/^[a-zA-Z0-9]+$/"
    ],
    "email" => [
        "type" => "string",
    ],
    "password" => [
        "type" => "string",
        "min" => 8,
        "max" => 50,
        "regex" => "/^[a-zA-Z0-9]+$/"
    ]
]);




/**
 * our middleware object with all the necessary methods to check the incoming request
 * @see middleware/Middleware.php
 * 
 * @throws Error 405 Method Not Allowed
 * @throws Error 400 Bad Request
 * @throws Error 401 Unauthorized
 * 
 */
$middleware = new Middleware($request);




/**
 * our response object with all the necessary methods to send a response to the client
 * @see http/Response.php
 */
$response = new Response([
    "code" => 200,
    "message" => "Produit trouvé",
    "header" => [
        "methods" => [Signup::ENDPOINT_METHOD]
    ]
]);





// Create the endpoint with above configuration
$endpoint = new Signup($request, $response, $middleware, $validator);




// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
