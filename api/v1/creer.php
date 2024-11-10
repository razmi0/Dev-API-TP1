<?php

use Core\Endpoint;
use HTTP\{Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\ProductDao, Entity\Product};

require_once "../../vendor/autoload.php";

// _____ _______ _______ _______ _______ _______ _______ _______
//|                                                             |
//|                 CREATE PRODUCT ENDPOINT                     |
//|                                                             |
//|          endpoint :  [POST] /api/v1.0/produit/new           |
//|          file     :         /api/v1/creer.php               |
//|          goal     :  create a product in database           |
//|_____________________________________________________________|
//



/**
 * 
 * CreateEndpoint
 * 
 * This class extends the Endpoint class.
 * The parent Endpoint class is an abstract class that defines the basic structure of an endpoint and
 * the CreateEndpoint class is a concrete class that implements the logic of the create endpoint.
 * 
 * @property Request $request
 * @property Response $response
 * @property Middleware $middleware
 * @property Validator $validator
 * 
 * @method __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
 * @method handleMiddleware(): void
 * @method handleRequest(): array
 * @method handleResponse(mixed $data): void
 * 
 */
final class CreateEndpoint extends Endpoint
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
     * ✅ The middleware object will handle all the checks to avoid a bad request
     */
    public function handleMiddleware(): void
    {

        // Check if the request method is allowed (POST only)
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);            // if error return 405 Method Not Allowed

        // Check if the request body is a valid JSON
        $this->middleware->checkValidJson();                                        // if error, return 400 Bad Request

        // Check if the request body contains the expected data 
        // (name type, length, regex ; description type, length, regex ; ect...)
        $this->middleware->checkExpectedData($this->validator);                        // if error, return 400 Bad Request

    }

    /**
     * 🧠 Handle the core logic of the endpoint
     * @return array The data to send back to the client
     */
    public function handleRequest(): array
    {
        // Decoded body from the request
        $client_data = $this->request->getDecodedBody();

        // Create a new product
        $newProduct = Product::make($client_data);

        // Start the DAO
        $dao = new ProductDao();

        // The DAO create method create a new product in the database and return the inserted ID
        $insertedID = $dao->create($newProduct);

        // Cast the id to integer and return the inserted ID  
        return ["id" => (int)$insertedID];
    }

    /**
     *  📡 Handle the response to the client, setting the payload and sending the response
     */
    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}


// ENDPOINT INSTRUCTIONS 👇
// --




/**
 * our request object with all incoming informations (headers, body, method, query string etc...)
 * @see http/Request.php
 */
$request = new Request();






// our template rules to validate the client data in the request body
$validator = new Validator(
    [
        "name" => [
            "type" => "string",
            "range" => [1, 65],
            "regex" => Constant::NAME_REGEX,
            "required" => true
        ],
        "description" => [
            "type" => "string",
            "range" => [1, 65000],
            "regex" => Constant::DESCRIPTION_REGEX,
            "required" => true
        ],
        "prix" => [
            "type" => "double",
            "range" => [0, null],
            "regex" => Constant::PRICE_REGEX,
            "required" => true
        ]
    ]
);





/**
 * Our middleware object that will handle all the checks to avoid a bad request and validate the incoming request
 * @see middleware/Middleware.php
 * 
 * @throws HTTP 405 Method Not Allowed
 * @throws HTTP 400 Bad Request
 */
$middleware = new Middleware($request);






/**
 * Our basic configuration of the response object in case of success
 * @see http/Response.php
 */
$response = new Response([
    "code" => 201,
    "message" => "Produit créé avec succès",
    "header" => [
        "methods" => [CreateEndpoint::ENDPOINT_METHOD]
    ]
]);






// Create the endpoint object with above configuration
$endpoint = new CreateEndpoint($request, $response, $middleware, $validator);





// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
