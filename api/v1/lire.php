<?php

namespace API\Endpoints;

use API\Controllers\BaseEndpoint;
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
/**
 * @OA\Get(
 *     path="/TP1/api/v1.0/produit/list",
 *     operationId="findAllProducts",
 *     tags={"Product", "READ"},
 *     description="This endpoint allows you to retrieve all the products from the database. The parameter limit is optional and will limit the number of products returned. The parameter limit can be passed as a query parameter or in the request body",
 *     summary="Retrieve all products",
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="The maximum number of products to retrieve",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             minimum=1,
 *             maximum=100,
 *             example=2
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="limit",
 *                 type="integer",
 *                 description="The maximum number of products to retrieve",
 *                 example=2
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success : the products have been retrieved. The response contains a list of products in a JSON array format in the products key.",
 *         @OA\JsonContent(ref="#/components/schemas/SUCCESS_LIST_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request : the request body is not valid",
 *         @OA\JsonContent(ref="#/components/schemas/BAD_REQUEST_RESPONSE_LIST")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not found : no products have been found in the database",
 *         @OA\JsonContent(ref="#/components/schemas/NOT_FOUND_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=405,
 *         description="Method not allowed : only GET method is allowed",
 *         @OA\JsonContent(ref="#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error : an error occured on the server",
 *         @OA\JsonContent(ref="#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE")
 *     )
 * )
 */
final class ListEndpoint extends BaseEndpoint
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

        // Check if the request come from a user with a valid token
        $this->middleware->checkAuthorization();                      // if error return 401 Unauthorized

        // Check if the request body is a valid JSON
        $this->middleware->checkValidJson();                // if error return 400 Bad Request

        // Check if the request body contains the expected data
        $this->middleware->checkExpectedData($this->validator); // if error return 400 Bad Request



        // sanitize the data ( all types )
        // get the decoded body from the request, sanitize it
        // and set it back to the request object
        $this->middleware->sanitizeData(                                  // // no error expected
            ["sanitize" => ["html", "integer", "float"]]
        );
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
            $limit = (int)$this->request->getDecodedData("limit");
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
        // Send the response with a 200 status code and a success message if all went well
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
            "nullable" => true,
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
