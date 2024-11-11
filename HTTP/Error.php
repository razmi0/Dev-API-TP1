<?php

namespace HTTP;

require_once "../../vendor/autoload.php";

use OpenApi\Attributes as OA;
use Utils\Console;

/**
 * Class Error
 * 
 */
#[OA\Components(
    [

        // Schema for the 200 response (list)
        new OA\Schema(
            schema: "SUCCESS_LIST_RESPONSE",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: ""
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "The success message",
                    example: "Liste des produits récupérée avec succès"
                ),
                new OA\Property(
                    property: "data",
                    type: "object",
                    description: "The product key is an array with all products",
                    properties: [
                        new Oa\Property(
                            property: "products",
                            type: "array",
                            description: "The list of products",
                            items: new OA\Items(
                                type: "object",
                                properties: [
                                    new OA\Property(
                                        property: "id",
                                        type: "integer",
                                        description: "The id of the product",
                                        example: 1
                                    ),
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
                                        example: "Description of product 1"
                                    ),
                                    new OA\Property(
                                        property: "prix",
                                        type: "number",
                                        description: "The price of the product",
                                        example: 10.99
                                    ),
                                    new OA\Property(
                                        property: "date_creation",
                                        type: "string",
                                        description: "The creation date of the product",
                                        example: "2021-09-01 12:00:00"
                                    )
                                ],
                            ),
                        )
                    ],
                    example: [
                        "products" => [
                            [
                                "id" => 1,
                                "name" => "Product 1",
                                "description" => "Description of product 1",
                                "prix" => 10.99,
                                "date_creation" => "2021-09-01 12:00:00"
                            ],
                            [
                                "id" => 2,
                                "name" => "Product 2",
                                "description" => "Description of product 2",
                                "prix" => 20.99,
                                "date_creation" => "2021-09-01 12:00:00"
                            ]
                        ]
                    ]


                )

            ],
        ),



        // Schema for the 200 response (listone)
        new OA\Schema(
            schema: "SUCCESS_LISTONE_RESPONSE",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: ""
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "The success message",
                    example: "Produit récupéré avec succès"
                ),
                new OA\Property(
                    property: "data",
                    type: "object",
                    description: "The product key is an array with a single product",
                    properties: [
                        new Oa\Property(
                            property: "product",
                            type: "object",
                            description: "The product",
                            properties: [
                                new OA\Property(
                                    property: "id",
                                    type: "integer",
                                    description: "The id of the product",
                                    example: 1
                                ),
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
                                    example: "Description of product 1"
                                ),
                                new OA\Property(
                                    property: "prix",
                                    type: "number",
                                    description: "The price of the product",
                                    example: 10.99
                                ),
                                new OA\Property(
                                    property: "date_creation",
                                    type: "string",
                                    description: "The creation date of the product",
                                    example: "2021-09-01 12:00:00"
                                )

                            ],
                        )
                    ],
                    example: [
                        "product" => [
                            "id" => 1,
                            "name" => "Product 1",
                            "description" => "Description of product 1",
                            "prix" => 10.99,
                            "date_creation" => "2021-09-01 12:00:00"
                        ]
                    ]

                )
            ],
        ),



        // Schema for the 200 response (listmany)
        new OA\Schema(
            schema: "SUCCESS_LISTMANY_RESPONSE",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: ""
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "The success message",
                    example: "Liste des produits récupérée avec succès"
                ),
                new OA\Property(
                    property: "data",
                    type: "object",
                    description: "The product key is an array with all products",
                    properties: [
                        new Oa\Property(
                            property: "products",
                            type: "array",
                            description: "The list of products",
                            items: new OA\Items(
                                type: "object",
                                properties: [
                                    new OA\Property(
                                        property: "id",
                                        type: "integer",
                                        description: "The id of the product",
                                        example: 47
                                    ),
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
                                        example: "Description of product 1"
                                    ),
                                    new OA\Property(
                                        property: "prix",
                                        type: "number",
                                        description: "The price of the product",
                                        example: 10.99
                                    ),
                                    new OA\Property(
                                        property: "date_creation",
                                        type: "string",
                                        description: "The creation date of the product",
                                        example: "2021-09-01 12:00:00"
                                    )
                                ],
                            ),
                        )
                    ],
                    example: [
                        "products" => [
                            [
                                "id" => 47,
                                "name" => "Product 47",
                                "description" => "Description of product 47",
                                "prix" => 10.99,
                                "date_creation" => "2021-09-01 12:00:00"
                            ],
                            [
                                "id" => 48,
                                "name" => "Product 48",
                                "description" => "Description of product 48",
                                "prix" => 20.99,
                                "date_creation" => "2021-09-01 12:00:00"
                            ]
                        ]
                    ]

                )
            ]
        ),

        // Schema for the 200 response (delete)
        new OA\Schema(
            schema: "SUCCESS_DELETE_RESPONSE",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: ""
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "The success message",
                    example: "Produit supprimé avec succès"
                ),
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "An empty array",
                    items: new OA\Items(
                        type: "null",
                    ),
                    example: []
                )

            ],

        ),


        // Schema for the 200 response (update)
        new OA\Schema(
            schema: "SUCCESS_UPDATE_RESPONSE",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: ""
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "The success message",
                    example: "Produit modifié avec succès"
                ),
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "An empty array",
                    items: new OA\Items(
                        type: "null",
                    ),
                    example: []
                )

            ],

        ),



        // Schema for the 201 response (created)
        new OA\Schema(
            schema: "SUCCESS_CREATED_RESPONSE",
            type: "object",
            properties: [

                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: ""
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "The success message",
                    example: "Produit créé avec succès"
                ),
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "The id newly created product",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "id",
                                type: "integer",
                                description: "The id of the newly created product",
                                example: 46
                            )
                        ],
                    ),
                    example: [
                        "id" => 46
                    ]
                )

            ],
        ),





        // Schema for the basic 400 response properties (error and message)
        new OA\Schema(
            schema: "BASIC_ERROR_400_RESPONSE_BODY",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: "Invalid request"
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "Will always be empty in an error response",
                    example: ""
                )
            ]
        ),







        // Schema for the 400 response (created)
        new OA\Schema(
            schema: "BAD_REQUEST_RESPONSE_CREATED",
            type: "object",
            oneOf: [
                new OA\Schema(
                    ref: "#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
                )
            ],
            properties: [

                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "The list of errors",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "code",
                                type: "string",
                                description: "The field that caused the error, will be displayed like : invalid_<field>",
                                example: "invalid_type"
                            ),
                            new OA\Property(
                                property: "expected",
                                type: "string",
                                description: "The expected data of the field",
                                example: "string"

                            ),
                            new OA\Property(
                                property: "received",
                                type: "string",
                                description: "The received data of the field",
                                example: "integer"
                            ),
                            new OA\Property(
                                property: "path",
                                type: "array",
                                items: new OA\Items(
                                    type: "string"
                                ),
                                description: "The path of the field in the JSON object",
                                example: ["name"]
                            ),
                            new OA\Property(
                                property: "message",
                                type: "string",
                                description: "A message explaining briefly the error",
                                example: "Value does not match the expected type",
                            ),
                        ],

                    )
                )

            ],

        ),







        // Schema for the 400 Response (list)
        new OA\Schema(
            schema: "BAD_REQUEST_RESPONSE_LIST",
            type: "object",
            oneOf: [
                new OA\Schema(
                    ref: "#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
                )
            ],
            properties: [
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "The list of errors",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "code",
                                type: "string",
                                description: "The field that caused the error, will be displayed like : invalid_<field>",
                                example: "invalid_regex"
                            ),
                            new OA\Property(
                                property: "expected",
                                type: "string",
                                description: "The expected data of the field",
                                example: "/^[0-9]+$/"

                            ),
                            new OA\Property(
                                property: "received",
                                type: "string",
                                description: "The received data of the field",
                                example: "Hello world"
                            ),
                            new OA\Property(
                                property: "path",
                                type: "array",
                                items: new OA\Items(
                                    type: "string"
                                ),
                                description: "The path of the field in the JSON object",
                                example: ["limit"]
                            ),
                            new OA\Property(
                                property: "message",
                                type: "string",
                                description: "A message explaining briefly the error",
                                example: "Value does not match the expected regex",
                            ),
                        ],

                    )
                )
            ]

        ),




        // Schema for the 400 Response (update)
        new OA\Schema(
            schema: "BAD_REQUEST_RESPONSE_UPDATE",
            type: "object",
            oneOf: [
                new OA\Schema(
                    ref: "#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
                )
            ],
            properties: [
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "The list of errors",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "code",
                                type: "string",
                                description: "The field that caused the error, will be displayed like : invalid_<field>",
                                example: "invalid_type"
                            ),
                            new OA\Property(
                                property: "expected",
                                type: "float",
                                description: "The expected data of the field",
                                example: "float"

                            ),
                            new OA\Property(
                                property: "received",
                                type: "integer",
                                description: "The received data of the field",
                                example: "integer"
                            ),
                            new OA\Property(
                                property: "path",
                                type: "array",
                                items: new OA\Items(
                                    type: "string"
                                ),
                                description: "The path of the field in the JSON object",
                                example: ["pruix"]
                            ),
                            new OA\Property(
                                property: "message",
                                type: "string",
                                description: "A message explaining briefly the error",
                                example: "Value does not match the expected type",
                            ),
                        ],

                    )
                )
            ]
        ),




        // Schema for the 400 Response (listone)
        new OA\Schema(
            schema: "BAD_REQUEST_RESPONSE_LISTONE",
            type: "object",
            oneOf: [
                new OA\Schema(
                    ref: "#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
                )
            ],
            properties: [
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "The list of errors",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "code",
                                type: "string",
                                description: "The field that caused the error, will be displayed like : invalid_<field>",
                                example: "invalid_range"
                            ),
                            new OA\Property(
                                property: "expected",
                                type: "string",
                                description: "The expected data of the field",
                                example: "[1, + infinity]"

                            ),
                            new OA\Property(
                                property: "received",
                                type: "string",
                                description: "The received data of the field",
                                example: "0"
                            ),
                            new OA\Property(
                                property: "path",
                                type: "array",
                                items: new OA\Items(
                                    type: "string"
                                ),
                                description: "The path of the field in the JSON object",
                                example: ["id"]
                            ),
                            new OA\Property(
                                property: "message",
                                type: "string",
                                description: "A message explaining briefly the error",
                                example: "Value is inferior to the minimum threshold",
                            ),
                        ],


                    ),
                    example: [
                        [
                            "code" => "invalid_range",
                            "expected" => "[1, + infinity]",
                            "received" => "0",
                            "path" => ["id"],
                            "message" => "Value is inferior to the minimum threshold"
                        ]
                    ]
                )
            ]

        ),


        // Schema for the 400 Response (listmany)
        new OA\Schema(
            schema: "BAD_REQUEST_RESPONSE_LISTMANY",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: "Invalid request"
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "Some information there",
                    example: "Aucun ids de produits n'a été fourni dans la requête."
                ),
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "an empty array",
                    items: new OA\Items(
                        type: "null",
                    ),
                    example: []
                )
            ]
        ),







        // Schema for the 405 response
        new OA\Schema(
            schema: "METHOD_NOT_ALLOWED_RESPONSE",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: "Méthode non autorisée"
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "Will be empty",
                    example: ""
                ),
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "An empty array",
                    items: new OA\Items(
                        type: "null",
                    ),
                    example: []
                )
            ],

        ),







        // Schema for the 500 response
        new OA\Schema(
            schema: "INTERNAL_SERVER_ERROR_RESPONSE",
            type: "object",
            properties: [
                new OA\Property(
                    property: "error",
                    type: "string",
                    description: "The error message",
                    example: "Erreur interne"
                ),
                new OA\Property(
                    property: "message",
                    type: "string",
                    description: "Will be empty",
                    example: ""
                ),
                new OA\Property(
                    property: "data",
                    type: "array",
                    description: "An empty array",
                    items: new OA\Items(
                        type: "null",
                    ),
                    example: []
                )
            ],
        ),
    ],
)]
class Error
{
    private $code = 0;
    private $message = null;
    private $error = null;
    private $data = [];

    private function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    private function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    private function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    private function setPayload($data)
    {
        $this->data = $data;
        return $this;
    }

    private function sendAndDie()
    {
        Console::log($this->message, $this->error, $this->data, $this->code);
        header("Content-Type: application/json; charset=UTF-8");

        $payload = new Payload([
            "message" => $this->message ?? "",
            "data" => $this->data ?? [],
            "error" => $this->error ?? "",
        ]);

        http_response_code($this->code);
        echo $payload->toJson();
        die();
    }

    /**
     * HTTP404
     * 
     * 404 Not found error 
     * 
     */
    public static function HTTP404(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(404)
            ->setMessage($msg)
            ->setError("Ressource non trouvée")
            ->setPayload($payload)
            ->sendAndDie();
    }

    /**
     * HTTP405
     * 
     * 405 Method not allowed error
     * 
     */
    public static function HTTP405(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(405)
            ->setMessage($msg)
            ->setError("Méthode non autorisée")
            ->setPayload($payload)
            ->sendAndDie();
    }

    /**
     * HTTP400
     * 
     * 400 Bad request error
     * 
     */
    public static function HTTP400(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(400)
            ->setMessage($msg)
            ->setError("Requête invalide")
            ->setPayload($payload)
            ->sendAndDie();
    }

    /**
     * HTTP500
     * 
     * 500 Internal server error
     * 
     */
    public static function HTTP500(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(500)
            ->setMessage($msg)
            ->setError("Erreur interne")
            ->setPayload($payload)
            ->sendAndDie();
    }

    /**
     * HTTP204
     * 
     * 204 No content error (no data to return)
     * 
     */
    public static function HTTP204(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(204)
            ->setMessage($msg)
            ->setError("Aucun contenu")
            ->setPayload($payload)
            ->sendAndDie();
    }

    /**
     * HTTP503
     * 
     * 503 Service unavailable error
     * 
     */
    public static function HTTP503(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(503)
            ->setMessage($msg)
            ->setError("Service non disponible")
            ->setPayload($payload)
            ->sendAndDie();
    }
}
