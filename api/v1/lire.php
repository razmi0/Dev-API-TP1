<?php

namespace API\Endpoints;

use Core\Endpoint;
use HTTP\{Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\ProductDao, Entity\Product};

require_once "../../vendor/autoload.php";

//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                  READ PRODUCT ENDPOINT                      |
// |                                                             |
// |          endpoint :  [GET] /api/v1.0/produit/list           |
// |          param    :           limit                         |
// |          file     :        /api/v1/lire.php                 |
// |          goal     :  retrieve all products from db          |
// |_____________________________________________________________|
//


/**
 * ListEndpoint
 * 
 * This class extends the Endpoint class.
 * The parent Endpoint class is an abstract class that defines the basic structure of an endpoint and
 * the ListEndpoint class is a concrete class that implements the logic of the list endpoint.
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
final class ListEndpoint extends Endpoint
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
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);      // if error return 405 Method Not Allowed

        // Check if the request body is a valid JSON
        $this->middleware->checkValidJson();                // if error return 400 Bad Request

        // Check if the request body contains the expected data
        $this->middleware->checkExpectedData($this->validator); // if error return 400 Bad Request
    }

    /**
     * 🧠  The request object will handle the business logic
     */
    public function handleRequest(): array
    {
        /**
         * Check if the limit is in the query or in the body
         * limit is optional and if not provided, all products are returned by the dao
         */
        $isLimitInQuery = $this->request->getHasQuery();
        $isLimitInBody = $this->request->getHasData();


        /**
         * Get the limit and cast it to an integer if it is from the query
         * @var int|null  $limit
         */
        $limit = null;
        if ($isLimitInQuery)
            $limit = (int)$this->request->getQueryParam("limit");
        else if ($isLimitInBody)
            $limit = (int)$this->request->getDecodedBody("limit");
        // else is not necessary here, limit is null


        /**
         * Create a new ProductDao object
         * @see model/dao/ProductDao.php
         */
        $dao = new ProductDao();

        // Get all products from the database (with a limit if provided)
        /**
         * @var Product[] $allProducts
         */
        $allProducts = $dao->findAll($limit);

        // Map the products to an array
        $productsArray = array_map(fn($product) => $product->toArray(), $allProducts);

        // Return the products array
        return ["products" => $productsArray];
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
$validator = new Validator(
    [
        "limit" => [
            "type" => "integer",
            "required" => false,
            "range" => [1, null],
            "regex" => Constant::ID_REGEX
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
    "code" => 200,
    "message" => "Produits récupérés avec succès",
    "header" => [
        "methods" => [ListEndpoint::ENDPOINT_METHOD]
    ]
]);



// Create the endpoint object with above configuration
$endpoint = new ListEndpoint($request, $response, $middleware, $validator);



// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
