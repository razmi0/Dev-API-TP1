<?php

namespace HTTP;

require_once "../vendor/autoload.php";

use HTTP\Payload;
use Utils\Console;

/**
 * @OA\Components(
 *     schemas={
 *         @OA\Schema(
 *             schema="SUCCESS_LIST_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="The success message",
 *                     example="Liste des produits récupérée avec succès"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="object",
 *                     description="The product key is an array with all products",
 *                     properties={
 *                         @OA\Property(
 *                             property="products",
 *                             type="array",
 *                             description="The list of products",
 *                             @OA\Items(
 *                                 type="object",
 *                                 properties={
 *                                     @OA\Property(
 *                                         property="id",
 *                                         type="integer",
 *                                         description="The id of the product",
 *                                         example=1
 *                                     ),
 *                                     @OA\Property(
 *                                         property="name",
 *                                         type="string",
 *                                         description="The name of the product",
 *                                         example="Product 1"
 *                                     ),
 *                                     @OA\Property(
 *                                         property="description",
 *                                         type="string",
 *                                         description="The description of the product",
 *                                         example="Description of product 1"
 *                                     ),
 *                                     @OA\Property(
 *                                         property="prix",
 *                                         type="number",
 *                                         description="The price of the product",
 *                                         example=10.99
 *                                     ),
 *                                     @OA\Property(
 *                                         property="date_creation",
 *                                         type="string",
 *                                         description="The creation date of the product",
 *                                         example="2021-09-01 12:00:00"
 *                                     )
 *                                 }
 *                             )
 *                         )
 *                     },
 *                     example={
 *                         "products"={
 *                             {
 *                                 "id"=1,
 *                                 "name"="Product 1",
 *                                 "description"="Description of product 1",
 *                                 "prix"=10.99,
 *                                 "date_creation"="2021-09-01 12:00:00"
 *                             },
 *                             {
 *                                 "id"=2,
 *                                 "name"="Product 2",
 *                                 "description"="Description of product 2",
 *                                 "prix"=20.99,
 *                                 "date_creation"="2021-09-01 12:00:00"
 *                             }
 *                         }
 *                     }
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="SUCCESS_LISTONE_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="The success message",
 *                     example="Produit récupéré avec succès"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="object",
 *                     description="The product key is an array with a single product",
 *                     properties={
 *                         @OA\Property(
 *                             property="product",
 *                             type="object",
 *                             description="The product",
 *                             properties={
 *                                 @OA\Property(
 *                                     property="id",
 *                                     type="integer",
 *                                     description="The id of the product",
 *                                     example=1
 *                                 ),
 *                                 @OA\Property(
 *                                     property="name",
 *                                     type="string",
 *                                     description="The name of the product",
 *                                     example="Product 1"
 *                                 ),
 *                                 @OA\Property(
 *                                     property="description",
 *                                     type="string",
 *                                     description="The description of the product",
 *                                     example="Description of product 1"
 *                                 ),
 *                                 @OA\Property(
 *                                     property="prix",
 *                                     type="number",
 *                                     description="The price of the product",
 *                                     example=10.99
 *                                 ),
 *                                 @OA\Property(
 *                                     property="date_creation",
 *                                     type="string",
 *                                     description="The creation date of the product",
 *                                     example="2021-09-01 12:00:00"
 *                                 )
 *                             }
 *                         )
 *                     },
 *                     example={
 *                         "product"={
 *                             "id"=1,
 *                             "name"="Product 1",
 *                             "description"="Description of product 1",
 *                             "prix"=10.99,
 *                             "date_creation"="2021-09-01 12:00:00"
 *                         }
 *                     }
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="SUCCESS_LISTMANY_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="The success message",
 *                     example="Liste des produits récupérée avec succès"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="object",
 *                     description="The product key is an array with all products",
 *                     properties={
 *                         @OA\Property(
 *                             property="products",
 *                             type="array",
 *                             description="The list of products",
 *                             @OA\Items(
 *                                 type="object",
 *                                 properties={
 *                                     @OA\Property(
 *                                         property="id",
 *                                         type="integer",
 *                                         description="The id of the product",
 *                                         example=47
 *                                     ),
 *                                     @OA\Property(
 *                                         property="name",
 *                                         type="string",
 *                                         description="The name of the product",
 *                                         example="Product 1"
 *                                     ),
 *                                     @OA\Property(
 *                                         property="description",
 *                                         type="string",
 *                                         description="The description of the product",
 *                                         example="Description of product 1"
 *                                     ),
 *                                     @OA\Property(
 *                                         property="prix",
 *                                         type="number",
 *                                         description="The price of the product",
 *                                         example=10.99
 *                                     ),
 *                                     @OA\Property(
 *                                         property="date_creation",
 *                                         type="string",
 *                                         description="The creation date of the product",
 *                                         example="2021-09-01 12:00:00"
 *                                     )
 *                                 }
 *                             )
 *                         )
 *                     },
 *                     example={
 *                         "products"={
 *                             {
 *                                 "id"=47,
 *                                 "name"="Product 47",
 *                                 "description"="Description of product 47",
 *                                 "prix"=10.99,
 *                                 "date_creation"="2021-09-01 12:00:00"
 *                             },
 *                             {
 *                                 "id"=48,
 *                                 "name"="Product 48",
 *                                 "description"="Description of product 48",
 *                                 "prix"=20.99,
 *                                 "date_creation"="2021-09-01 12:00:00"
 *                             }
 *                         }
 *                     }
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="SUCCESS_DELETE_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="The success message",
 *                     example="Produit supprimé avec succès"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="An empty array",
 *                     @OA\Items(
 *                         type="null"
 *                     ),
 *                     example={}
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="SUCCESS_UPDATE_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="The success message",
 *                     example="Produit modifié avec succès"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="An empty array",
 *                     @OA\Items(
 *                         type="null"
 *                     ),
 *                     example={}
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="SUCCESS_CREATED_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="The success message",
 *                     example="Produit créé avec succès"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="The id newly created product",
 *                     @OA\Items(
 *                         type="object",
 *                         properties={
 *                             @OA\Property(
 *                                 property="id",
 *                                 type="integer",
 *                                 description="The id of the newly created product",
 *                                 example=46
 *                             )
 *                         }
 *                     ),
 *                     example={
 *                         "id"=46
 *                     }
 *                 )
 *             }
 *         )
 *       }
 * )
 */
class Response
{
    private ?int $code = null;

    private array $header = [];

    // Default payload
    private ?Payload $payload = null;

    // The constructor sets a default configuration for the response
    // Code and methods are required
    public function __construct($config)
    {


        // valid config now
        $this->code = $config["code"];

        $this->payload = new Payload([
            "message" => $config["message"] ?? "",
            "data" =>  $config["data"] ?? [],
            "error" => $config["error"] ?? ""
        ]);

        // keep a default header if not provided
        $this->header = [
            "Access-Control-Allow-Methods: " => self::methodsToString($config["header"]["methods"]), // default ["GET"]
            "Content-Type: " => $config["header"]["content_type"] ?? "application/json", // default "application/json"
            "Access-Control-Allow-Origin: " => $config["header"]["origin"] ?? "*", // default "*"
            "Access-Control-Age: " => $config["header"]["age"] ?? 3600, // default 3600
            "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" => ""
        ];

        if (isset($config["header"]["location"])) {
            $this->header = [...$this->header, "Location: " => $config["header"]["location"]];
        }
    }

    private static function methodsToString(array $methods): string
    {
        return implode(", ", $methods);
    }

    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }

    public function setMessage($message): self
    {
        $this->payload->setMessage($message);
        return $this;
    }

    public function setPayload($data): self
    {
        $this->payload->setData($data);
        return $this;
    }

    public function setError(string $error): self
    {
        $this->payload->setError($error);
        return $this;
    }

    public function setContentType(string $content_type): self
    {
        $this->header = [...$this->header, "content_type" => $content_type];
        return $this;
    }

    public function setOrigin(string $origin): self
    {
        $this->header = [...$this->header, "origin" => $origin];
        return $this;
    }

    public function setMethods(array $methods): self
    {
        $this->header = [...$this->header, "methods" => self::methodsToString($methods)];
        return $this;
    }

    public function setAge($age): self
    {
        $this->header = [...$this->header, "age" => $age];
        return $this;
    }

    public function sendAndDie()
    {

        // set the headers
        foreach ($this->header as $key => $value) {
            header($key . $value);
        }

        // log the response to error log for debugging
        Console::log(
            $this->payload->getMessage(),
            $this->payload->getError(),
            $this->payload->getData(),
            $this->code
        );

        // set the response code
        http_response_code($this->code);

        // send the payload to the client
        echo $this->payload->toJson();

        // end the script

        die();
    }
}
