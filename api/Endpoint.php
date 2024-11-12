<?php

namespace API;

use HTTP\{Request, Response};
use Middleware\Middleware;
use Exception;
use Middleware\Validators\Validator;
use OpenApi\Annotations as OA;

/**
 * 
 * class Endpoint
 * 
 * This class is the base class for all the endpoints.
 * It is a abstract class that will be extended by all the endpoints.
 * Endpoint enforce the implementation of the following handlers and 
 * provide the necessary properties to the child class.
 * 
 * 
 * @property Response $response
 * @property Request $request
 * @property Validator $validator
 * @property Middleware $middleware
 * 
 * @method handleMiddleware()
 * @method handleRequest()
 * @method handleResponse(mixed $payload)
 */

/**
 * @OA\Info(
 *     version="1.0",
 *     description="CRUD on a simple product entity",
 *     title="Product API",
 *     @OA\Contact(
 *         name="Thomas Cuesta",
 *         email="thomas.cuesta@my-digital-school.org",
 *         url="https://github.com/razmi0/Dev-API-TP1"
 *     )
 * )
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

/**
 * @OA\Delete(
 *     path="/TP1/api/v1.0/produit/delete",
 *     operationId="deleteProduct",
 *     tags={"Product", "DELETE"},
 *     description="This endpoint allows you to delete a product from the database. The parameter id is required and must be passed in the request body",
 *     summary="Delete a product",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 description="The id of the product to delete",
 *                 example=47
 *             ),
 *             required={"id"}
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success : the product has been deleted",
 *         @OA\JsonContent(ref="#/components/schemas/SUCCESS_DELETE_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="No content : the product has not been found in the database"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request : the request body is not valid",
 *         @OA\JsonContent(ref="#/components/schemas/BAD_REQUEST_RESPONSE_LISTONE")
 *     ),
 *     @OA\Response(
 *         response=405,
 *         description="Method not allowed : only DELETE method is allowed",
 *         @OA\JsonContent(ref="#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error : an error occured on the server",
 *         @OA\JsonContent(ref="#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE")
 *     )
 * )
 */

/**
 * @OA\Put(
 *     path="/TP1/api/v1.0/produit/update",
 *     operationId="updateProduct",
 *     tags={"Product", "UPDATE"},
 *     description="This endpoint allows you to update a product in the database. The only required body json field is the id of the product to update. The other fields are optional and will update the product in the database",
 *     summary="Update a product",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 description="The id of the product to update",
 *                 example=47
 *             ),
 *             @OA\Property(
 *                 property="prix",
 *                 type="float",
 *                 description="The new price of the product",
 *                 example=10.5
 *             ),
 *             required={"id"}
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success : the product has been updated",
 *         @OA\JsonContent(ref="#/components/schemas/SUCCESS_UPDATE_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="No content : the product has been found but update didn't change anything"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request : the request body is not valid",
 *         @OA\JsonContent(ref="#/components/schemas/BAD_REQUEST_RESPONSE_UPDATE")
 *     ),
 *     @OA\Response(
 *         response=405,
 *         description="Method not allowed : only PUT method is allowed",
 *         @OA\JsonContent(ref="#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error : an error occured on the server",
 *         @OA\JsonContent(ref="#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE")
 *     )
 * )
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
abstract class Endpoint
{
    protected Response $response;
    protected Request $request;
    protected Validator $validator;
    protected Middleware $middleware;

    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        /**
         * as we can't inforce child constants implementation with traits or interfaces in PHP 8.2.4 without hacky workarounds
         * we will throw an exception if the child class does not define the ENDPOINT_METHOD constant
         * @see https://stackoverflow.com/questions/10368620/abstract-constants-in-php-force-a-child-class-to-define-a-constant
         */
        if (!defined('static::ENDPOINT_METHOD'))
            throw new Exception("ENDPOINT_METHOD constant is not defined in the child class");

        $this->request = $request;
        $this->response = $response;
        $this->validator = $validator;
        $this->middleware = $middleware;
    }

    /**
     * ✅ Because all endpoints have identical checks, I centralized them here ( DRY principle)
     */
    abstract protected function handleMiddleware(): void;

    /**
     * 🧠 handleRequest contain the core logic of the endpoint, the business logic
     */
    abstract protected function handleRequest(): array;

    /**
     * 📡 handleResponse is responsible for sending the response back to the client
     */
    abstract protected function handleResponse(mixed $data): void;
}
