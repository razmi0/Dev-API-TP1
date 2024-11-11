<?php


namespace Core;

use HTTP\{Request, Response};
use Middleware\Middleware;
use Exception;
use Middleware\Validators\Validator;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Examples;

#[
    OA\Info(
        version: "1.0",
        description: "CRUD on a simple product entity",
        title: "Product API",
        contact: new OA\Contact(
            name: "Thomas Cuesta",
            email: "thomas.cuesta@my-digital-school.org",
            url: "https://github.com/razmi0/Dev-API-TP1"
        )
    ),

    // CreateEndpoint
    // @see /api/v1/creer.php
    // --

    OA\Post(
        path: "/TP1/api/v1.0/produit/new",
        tags: ["Product", "CREATE"],
        description: "This endpoint allows you to create a new product in database. Send a JSON object with the name, description and price of the product",
        summary: "Create a new product",
        responses: [
            new OA\Response(
                response: 201,
                description: "Ressource created",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/SUCCESS_CREATED_RESPONSE",
                ),

            ),

            new OA\Response(
                response: 400,
                description: "Bad request : the request body is not valid",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/BAD_REQUEST_RESPONSE_CREATED",


                ),

            ),
            new OA\Response(
                response: 405,
                description: "Method not allowed : only POST method is allowed",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE"
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error : an error occured on the server",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE"
                )
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "name",
                        type: "string",
                        description: "The name of the product",
                        example: "Product 1"

                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        description: "The description of the product",
                        example: "This is the first product"
                    ),
                    new OA\Property(
                        property: "price",
                        type: "float",
                        description: "The price of the product",
                        example: 10.5
                    ),

                ],
                required: ["name", "description", "price"],

            )
        ),


    ),


    // ListEndpoint
    // @see /api/v1/lire.php
    // --


    OA\GET(
        path: "/TP1/api/v1.0/produit/list",
        tags: ["Product", "READ"],
        description: "This endpoint allows you to retrieve all the products from the database. The parameter limit is optional and will limit the number of products returned. The parameter llit can be passed as a query parameter or in the request body",
        summary: "Retrieve all products",
        parameters: [
            new OA\Parameter(
                name: "limit",
                in: "query",
                description: "The maximum number of products to retrieve",
                required: false,
                schema: new OA\Schema(
                    type: "integer",
                    minimum: 1,
                    maximum: 100,
                    example: 2
                )
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "limit",
                        type: "integer",
                        description: "The maximum number of products to retrieve",
                        example: 2
                    )
                ],
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success : the products have been retrieved. The response contains a list of products in a JSON array format in the products key.",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/SUCCESS_LIST_RESPONSE"
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request : the request body is not valid",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/BAD_REQUEST_RESPONSE_LIST",
                )

            ),
            new OA\Response(
                response: 405,
                description: "Method not allowed : only GET method is allowed",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE"
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error : an error occured on the server",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE"
                )
            )
        ]
    ),


    // ListOneEndpoint
    // @see /api/v1/lire_un.php
    // --



    OA\GET(
        path: "/TP1/api/v1.0/produit/listone",
        tags: ["Product", "READ"],
        description: "This endpoint allows you to retrieve a single product from the database. The parameter id is required and must be passed as a query parameter or in the request body",
        summary: "Retrieve a single product",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "query",
                description: "The id of the product to retrieve",
                required: true,
                schema: new OA\Schema(
                    type: "integer",
                    minimum: 1,
                    example: 47
                )
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "id",
                        type: "integer",
                        description: "The id of the product to retrieve",
                        example: 47
                    )
                ],
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success : the product has been retrieved. The response contains the product in a JSON object format",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/SUCCESS_LISTONE_RESPONSE"
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request : the request body is not valid",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/BAD_REQUEST_RESPONSE_LISTONE",
                )

            ),
            new OA\Response(
                response: 405,
                description: "Method not allowed : only GET method is allowed",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE"
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error : an error occured on the server",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE"
                )
            )
        ]

    ),


    // DeleteEndpoint
    // @see /api/v1/supprimer.php
    // --




    OA\DELETE(
        path: "/TP1/api/v1.0/produit/delete",
        tags: ["Product", "DELETE"],
        description: "This endpoint allows you to delete a product from the database. The parameter id is required and must be passed in the request body",
        summary: "Delete a product",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "id",
                        type: "integer",
                        description: "The id of the product to delete",
                        example: 47
                    )
                ],
                required: ["id"]
            )
        ),

        responses: [
            new OA\Response(
                response: 200,
                description: "Success : the product has been deleted",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/SUCCESS_DELETE_RESPONSE"
                )
            ),
            new OA\Response(
                response: 204,
                description: "No content : the product has not been found in the database",
            ),
            new OA\Response(
                response: 400,
                description: "Bad request : the request body is not valid",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/BAD_REQUEST_RESPONSE_LISTONE",
                )

            ),
            new OA\Response(
                response: 405,
                description: "Method not allowed : only DELETE method is allowed",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE"
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error : an error occured on the server",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE"
                )
            )
        ]
    ),



    // UpdateEndpoint
    // @see /api/v1/modifier.php
    // --




    OA\PUT(
        path: "/TP1/api/v1.0/produit/update",
        tags: ["Product", "UPDATE"],
        description: "This endpoint allows you to update a product in the database. The only required body json field is the id of the product to update. The other fields are optional and will update the product in the database",
        summary: "Update a product",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "id",
                        type: "integer",
                        description: "The id of the product to update",
                        example: 47
                    ),
                    new OA\Property(
                        property: "price",
                        type: "float",
                        description: "The new price of the product",
                        example: 10.5
                    )
                ],
                required: ["id"]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success : the product has been updated",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/SUCCESS_UPDATE_RESPONSE"
                )
            ),

            new OA\Response(
                response: 204,
                description: "No content : the product has not been found in the database",
            ),
            new OA\Response(
                response: 400,
                description: "Bad request : the request body is not valid",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/BAD_REQUEST_RESPONSE_UPDATE",
                )

            ),
            new OA\Response(
                response: 405,
                description: "Method not allowed : only PUT method is allowed",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE"
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error : an error occured on the server",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE"
                )
            )
        ]
    ),




    // ListManyEndpoint
    // @see /api/v1/lire_des.php
    // --


    OA\GET(
        path: "/TP1/api/v1.0/produit/listmany/",
        tags: ["Product", "READ"],
        description: "This endpoint allows you to retrieve many products from the database. The parameter id[] is required and must be passed as a query parameter or in the request body",
        summary: "Retrieve many products",
        parameters: [
            new OA\Parameter(
                name: "id[]",
                in: "query",
                description: "The ids of the products to retrieve",
                required: true,
                explode: true,
                schema: new OA\Schema(
                    type: "array",
                    items: new OA\Items(
                        type: "integer",
                        minimum: 1,
                        example: 47
                    )
                ),
            )
        ],

        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(
                        property: "id",
                        type: "array",
                        description: "The ids of the products to retrieve",
                        items: new OA\Items(
                            type: "integer",
                            minimum: 1,
                        ),
                        example: [47, 48]
                    )
                ],
            )
        ),


        responses: [
            new OA\Response(
                response: 200,
                description: "Success : the products have been retrieved. The response contains a list of products in a JSON array format in the products key.",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/SUCCESS_LISTMANY_RESPONSE"
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request : the request body is not valid",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/BAD_REQUEST_RESPONSE_LISTMANY",
                )

            ),
            new OA\Response(
                response: 405,
                description: "Method not allowed : only GET method is allowed",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/METHOD_NOT_ALLOWED_RESPONSE"
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error : an error occured on the server",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/INTERNAL_SERVER_ERROR_RESPONSE"
                )
            )
        ]

    )


]
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
