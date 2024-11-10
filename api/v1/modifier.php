<?php


use Core\Endpoint;
use HTTP\{Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\ProductDao, Entity\Product};

require_once "../../vendor/autoload.php";

//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                 UPDATE PRODUCT ENDPOINT                     |
// |                                                             |
// |          endpoint :  [PUT] /api/v1.0/produit/update         |
// |          file     :        /api/v1/modifier.php             |
// |          goal     :  update a product in db                 |
// |_____________________________________________________________|
//



/**
 * 
 * UpdateEndpoint
 * 
 * This class extends the Endpoint class.
 * The parent Endpoint class is an abstract class that defines the basic structure of an endpoint and
 * the UpdateEndpoint class is a concrete class that implements the logic of the update endpoint.
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
final class UpdateEndpoint extends Endpoint
{



    // The only method allowed for this endpoint
    public const ENDPOINT_METHOD = "PUT";

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
        // Check if the request method is allowed else throw an error ( 405 Method Not Allowed )
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);

        // Check if the request body is a valid JSON else throw an error ( 400 Bad Request )
        $this->middleware->checkValidJson();

        // Check if the request body contains the expected data else throw an error ( 400 Bad Request )
        $this->middleware->checkExpectedData($this->validator);
    }

    /**
     * 🧠  The request object will handle the business logic
     */

    public function handleRequest(): array
    {
        // Get the client data
        /**
         * @var array Partial Product data
         */
        $client_data = $this->request->getDecodedBody();

        // Create a new product
        $product = Product::make($client_data);

        // Start the DAO
        $dao = new ProductDao();

        /**
         * Update the product in the database and store the number of affected rows
         * @var int $affected_rows
         */
        $affected_rows = $dao->update($product);


        // If no product was found, we send a 204 with no content in response body as HTTP specification states
        if ($affected_rows === 0) {
            $this->response->setCode(204);
        }

        // No response data to return, even if the product was updated
        return [];
    }

    /**
     * 📡 The response object will handle the response
     */
    public function handleResponse(mixed $data): void
    {
        // Send the response with a 200 status code and a success message
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



/**
 * our template rules to validate the client data in the request body
 * @see model/schema/Schema.php
 */
$validator = new Validator([
    "id" => [
        "type" => "integer",
        "required" => true,
        "range" => [1, null],
        "regex" => Constant::ID_REGEX
    ],
    "name" => [
        "type" => "string",
        "range" => [1, 65],
        "regex" => Constant::NAME_REGEX,
        "required" => false,
        "nullable" => true
    ],
    "description" => [
        "type" => "string",
        "range" => [1, 65000],
        "regex" => Constant::DESCRIPTION_REGEX,
        "required" => false,
        "nullable" => true
    ],
    "prix" => [
        "type" => "double",
        "range" => [0, null],
        "regex" => Constant::PRICE_REGEX,
        "required" => false,
        "nullable" => true
    ]
]);


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
    "code" => 200,
    "message" => "Produit modifié avec succès",
    "header" => [
        "methods" => [UpdateEndpoint::ENDPOINT_METHOD]
    ]
]);




// The endpoint is instanciated with the request, response, middleware and schema objects
$endpoint = new UpdateEndpoint($request, $response, $middleware, $validator);



// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
