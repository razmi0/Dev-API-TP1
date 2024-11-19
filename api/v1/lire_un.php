<?php

namespace API\Endpoints;


use API\Endpoint;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\ProductDao;

require_once "../../vendor/autoload.php";


//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                READ ONE PRODUCT ENDPOINT                    |
// |                                                             |
// |          endpoint :  [GET] /api/v1.0/produit/listone        |
// |          file     :        /api/v1/lire_un.php              |
// |          goal     :  retrieve many products from db         |
// |_____________________________________________________________|
//


/**
 * 
 * ListOneEndpoint
 * 
 * This class extends the Endpoint class.
 * The parent Endpoint class is an abstract class that defines the basic structure of an endpoint and
 * the ListOneEndpoint class is a concrete class that implements the logic of the create endpoint.
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
 *     path="/TP1/api/v1.0/produit/listone",
 *     operationId="findOneById",
 *     tags={"Product", "READ"},
 *     description="This endpoint allows you to retrieve a single product from the database. The parameter id is required and must be passed as a query parameter or in the request body",
 *     summary="Retrieve a single product",
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         description="The id of the product to retrieve",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             minimum=1,
 *             example=47
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 description="The id of the product to retrieve",
 *                 example=47
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success : the product has been retrieved. The response contains the product in a JSON object format",
 *         @OA\JsonContent(ref="#/components/schemas/SUCCESS_LISTONE_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request : the request body is not valid",
 *         @OA\JsonContent(ref="#/components/schemas/BAD_REQUEST_RESPONSE_LISTONE")
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
final class ListOneEndpoint extends Endpoint
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
     * 🧠 handleRequest contain the core logic of the endpoint
     */
    public function handleRequest(): array
    {

        /**
         * Check if the ids are present in the query or in the body
         */
        $isIdInQuery = $this->request->getHasQuery();
        $isIdInBody = $this->request->getHasData();

        // If the id is not present in the query or in the body, throw an error
        if (!$isIdInQuery && !$isIdInBody)
            Error::HTTP400("Aucun id de produit n'a été fourni dans la requête.");


        /**
         * Get the id and cast it to an integer if it is from the query
         * @var int $id
         */
        $id = $isIdInQuery
            ? (int)$this->request->getQueryParam("id")
            : $this->request->getDecodedData("id");

        // Start the DAO
        $dao = new ProductDao();

        // Get the product from the database
        $product = $dao->findById($id);

        // Return the product as an array
        return ["product" => $product->toArray()];
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
            "type" => "integer",
            "required" => false,
            "nullable" => true,
            "range" => [1, null],
            "regex" => Constant::ID_REGEX
        ],
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
    "message" => "Produit trouvé",
    "header" => [
        "methods" => [ListOneEndpoint::ENDPOINT_METHOD]
    ]
]);





// Create the endpoint with above configuration
$endpoint = new ListOneEndpoint($request, $response, $middleware, $validator);




// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
