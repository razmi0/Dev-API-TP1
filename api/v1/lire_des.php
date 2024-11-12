<?php

namespace API\Endpoints;

use API\Endpoint;
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

/**
 * @OA\Get(
 *     path="/TP1/api/v1.0/produit/listmany/{id[]}",
 *     operationId="findManyById",
 *     tags={"Product", "READ"},
 *     description="This endpoint allows you to retrieve many products from the database. The parameter id[] is required and must be passed as a query parameter or in the request body",
 *     summary="Retrieve many products",
 *     @OA\Parameter(
 *         name="id[]",
 *         in="query",
 *         description="The ids of the products to retrieve",
 *         required=true,
 *         explode=true,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(
 *                 type="integer",
 *                 minimum=1,
 *                 example=47
 *             )
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="id",
 *                 type="array",
 *                 description="The ids of the products to retrieve",
 *                 @OA\Items(
 *                     type="integer",
 *                     minimum=1
 *                 ),
 *                 example={47, 48}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success : the products have been retrieved. The response contains a list of products in a JSON array format in the products key.",
 *         @OA\JsonContent(ref="#/components/schemas/SUCCESS_LISTMANY_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request : the request body is not valid",
 *         @OA\JsonContent(ref="#/components/schemas/BAD_REQUEST_RESPONSE_LISTMANY")
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
