<?php

namespace API\Endpoints;

use Core\Endpoint;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\ProductDao, Entity\Product};

require_once "../../vendor/autoload.php";


//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |               READ MANY PRODUCT ENDPOINT                    |
// |                                                             |
// |          endpoint :  [GET] /api/v1.0/produit/listmany       |
// |          param    :               ids                       |
// |          file     :        /api/v1/lire_des.php             |
// |          goal     :  retrieve many products from db         |
// |_____________________________________________________________|
//




/**
 * ListManyEndpoint
 * 
 *This class extends the Endpoint class.
 * The parent Endpoint class is an abstract class that defines the basic structure of an endpoint and
 * the ListManyEndpoint class is a concrete class that implements the logic of the create endpoint.
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
final class ListManyEndpoint extends Endpoint
{

    // The only method allowed for this endpoint
    public const ENDPOINT_METHOD = "GET";

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

        // Check if the request method is allowed (GET only)
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);            // if error return 405 Method Not Allowed

        // Check if the request body is a valid JSON
        $this->middleware->checkValidJson();                                        // if error, return 400 Bad Request

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
     * 🧠 handleRequest contain the core logic of the endpoint
     */
    public function handleRequest(): array
    {

        /**
         * Check if the ids are present in the query or in the body
         */
        $isIdsInQuery = $this->request->getHasQuery();
        $isIdsInBody = $this->request->getHasData();


        // If the ids are not present in the query or in the body, throw an error
        if (!$isIdsInQuery && !$isIdsInBody) {
            Error::HTTP400("Aucun ids de produits n'a été fourni dans la requête.");
        }

        /**
         * Get the ids and cast them to an array of integers if they are from the query
         * @var int[] $ids
         */
        $ids = [];
        if ($isIdsInQuery)
            $ids = array_map(fn($id) => (int)$id, $this->request->getQueryParam("id"));
        else
            $ids = $this->request->getDecodedBody("id");


        // Start the DAO
        $dao = new ProductDao();

        /**
         * Get the product from the database
         * @var Product[] $products
         */
        $products = $dao->findManyById($ids);

        // Map the products to an array
        $productArray = array_map(fn($product) => $product->toArray(), $products);

        // Return the products array
        return ["products" => $productArray];
    }

    /**
     * 📡 handleResponse is responsible for sending the response back to the client
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
$validator = new Validator(
    [
        "id" => [
            "type" => "integer[]",
            "required" => false,
            "range" => [1, null],
            "regex" => Constant::ID_REGEX,
            "nullable" => true
        ]
    ]
);




/**
 * our middleware object with all the necessary methods to check the incoming request
 * @see middleware/Middleware.php
 * 
 * @throws Error 405 Method Not Allowed
 * @throws Error 400 Bad Request
 * 
 */
$middleware = new Middleware($request);





/**
 * our response object with all the necessary methods to send a response to the client
 * @see http/Response.php
 */
$response = new Response([
    "code" => 200,
    "message" => "Produits trouvés",
    "header" => [
        "methods" => [ListManyEndpoint::ENDPOINT_METHOD]
    ]
]);




// Create the endpoint with above configuration
$endpoint = new ListManyEndpoint($request, $response, $middleware, $validator);




// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
