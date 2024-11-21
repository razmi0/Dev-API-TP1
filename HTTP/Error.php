<?php

namespace HTTP;

require_once "../../vendor/autoload.php";

use OpenApi\Annotations as OA;
use Utils\Console;

/**
 * Class Error
 * 
 * @OA\Components(
 *     schemas={
 *         @OA\Schema(
 *             schema="BASIC_ERROR_400_RESPONSE_BODY",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example="Invalid request"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Will always be empty in an error response",
 *                     example=""
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="BAD_REQUEST_RESPONSE_CREATED",
 *             type="object",
 *             oneOf={
 *                 @OA\Schema(
 *                     ref="#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
 *                 )
 *             },
 *             properties={
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="The list of errors",
 *                     @OA\Items(
 *                         type="object",
 *                         properties={
 *                             @OA\Property(
 *                                 property="code",
 *                                 type="string",
 *                                 description="The field that caused the error, will be displayed like : invalid_<field>",
 *                                 example="invalid_type"
 *                             ),
 *                             @OA\Property(
 *                                 property="expected",
 *                                 type="string",
 *                                 description="The expected data of the field",
 *                                 example="string"
 *                             ),
 *                             @OA\Property(
 *                                 property="received",
 *                                 type="string",
 *                                 description="The received data of the field",
 *                                 example="integer"
 *                             ),
 *                             @OA\Property(
 *                                 property="path",
 *                                 type="array",
 *                                 @OA\Items(
 *                                     type="string"
 *                                 ),
 *                                 description="The path of the field in the JSON object",
 *                                 example={"name"}
 *                             ),
 *                             @OA\Property(
 *                                 property="message",
 *                                 type="string",
 *                                 description="A message explaining briefly the error",
 *                                 example="Value does not match the expected type"
 *                             )
 *                         }
 *                     )
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="BAD_REQUEST_RESPONSE_LIST",
 *             type="object",
 *             oneOf={
 *                 @OA\Schema(
 *                     ref="#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
 *                 )
 *             },
 *             properties={
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="The list of errors",
 *                     @OA\Items(
 *                         type="object",
 *                         properties={
 *                             @OA\Property(
 *                                 property="code",
 *                                 type="string",
 *                                 description="The field that caused the error, will be displayed like : invalid_<field>",
 *                                 example="invalid_type"
 *                             ),
 *                             @OA\Property(
 *                                 property="expected",
 *                                 type="string",
 *                                 description="The expected data of the field",
 *                                 example="string"
 *                             ),
 *                             @OA\Property(
 *                                 property="received",
 *                                 type="string",
 *                                 description="The received data of the field",
 *                                 example="integer"
 *                             ),
 *                             @OA\Property(
 *                                 property="path",
 *                                 type="array",
 *                                 @OA\Items(
 *                                     type="string"
 *                                 ),
 *                                 description="The path of the field in the JSON object",
 *                                 example={"name"}
 *                             ),
 *                             @OA\Property(
 *                                 property="message",
 *                                 type="string",
 *                                 description="A message explaining briefly the error",
 *                                 example="Value does not match the expected type"
 *                             )
 *                         }
 *                     )
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="NOT_FOUND_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example="Ressource non trouvée"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="The error message",
 *                     example="Aucun produit trouvé"
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
 *             schema="BAD_REQUEST_RESPONSE_UPDATE",
 *             type="object",
 *             oneOf={
 *                 @OA\Schema(
 *                     ref="#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
 *                 )
 *             },
 *             properties={
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="The list of errors",
 *                     @OA\Items(
 *                         type="object",
 *                         properties={
 *                             @OA\Property(
 *                                 property="code",
 *                                 type="string",
 *                                 description="The field that caused the error, will be displayed like : invalid_<field>",
 *                                 example="invalid_type"
 *                             ),
 *                             @OA\Property(
 *                                 property="expected",
 *                                 type="float",
 *                                 description="The expected data of the field",
 *                                 example="float"
 *                             ),
 *                             @OA\Property(
 *                                 property="received",
 *                                 type="integer",
 *                                 description="The received data of the field",
 *                                 example="integer"
 *                             ),
 *                             @OA\Property(
 *                                 property="path",
 *                                 type="array",
 *                                 @OA\Items(
 *                                     type="string"
 *                                 ),
 *                                 description="The path of the field in the JSON object",
 *                                 example={"pruix"}
 *                             ),
 *                             @OA\Property(
 *                                 property="message",
 *                                 type="string",
 *                                 description="A message explaining briefly the error",
 *                                 example="Value does not match the expected type"
 *                             )
 *                         }
 *                     )
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="BAD_REQUEST_RESPONSE_LISTONE",
 *             type="object",
 *             oneOf={
 *                 @OA\Schema(
 *                     ref="#/components/schemas/BASIC_ERROR_400_RESPONSE_BODY"
 *                 )
 *             },
 *             properties={
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="The list of errors",
 *                     @OA\Items(
 *                         type="object",
 *                         properties={
 *                             @OA\Property(
 *                                 property="code",
 *                                 type="string",
 *                                 description="The field that caused the error, will be displayed like : invalid_<field>",
 *                                 example="invalid_range"
 *                             ),
 *                             @OA\Property(
 *                                 property="expected",
 *                                 type="string",
 *                                 description="The expected data of the field",
 *                                 example="[1, + infinity]"
 *                             ),
 *                             @OA\Property(
 *                                 property="received",
 *                                 type="string",
 *                                 description="The received data of the field",
 *                                 example="0"
 *                             ),
 *                             @OA\Property(
 *                                 property="path",
 *                                 type="array",
 *                                 @OA\Items(
 *                                     type="string"
 *                                 ),
 *                                 description="The path of the field in the JSON object",
 *                                 example={"id"}
 *                             ),
 *                             @OA\Property(
 *                                 property="message",
 *                                 type="string",
 *                                 description="A message explaining briefly the error",
 *                                 example="Value is inferior to the minimum threshold"
 *                             )
 *                         }
 *                     ),
 *                     example={
 *                         {
 *                             "code"="invalid_range",
 *                             "expected"="[1, + infinity]",
 *                             "received"="0",
 *                             "path"={"id"},
 *                             "message"="Value is inferior to the minimum threshold"
 *                         }
 *                     }
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="BAD_REQUEST_RESPONSE_LISTMANY",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example="Invalid request"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Some information there",
 *                     example="Aucun ids de produits n'a été fourni dans la requête."
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     description="an empty array",
 *                     @OA\Items(
 *                         type="null"
 *                     ),
 *                     example={}
 *                 )
 *             }
 *         ),
 *         @OA\Schema(
 *             schema="METHOD_NOT_ALLOWED_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example="Méthode non autorisée"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Will be empty",
 *                     example=""
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
 *             schema="INTERNAL_SERVER_ERROR_RESPONSE",
 *             type="object",
 *             properties={
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="The error message",
 *                     example="Erreur interne"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Will be empty",
 *                     example=""
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
 *         )
 *    }
 * )
 */


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
     * HTTP401
     * 
     * 401 Unauthorized error
     */
    public static function HTTP401(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(401)
            ->setMessage($msg)
            ->setError("Non autorisé")
            ->setPayload($payload)
            ->sendAndDie();
    }

    /**
     * 
     * HTTP415
     * 
     * 415 Unsupported Media Type error
     */
    public static function HTTP415(string $msg, array $payload = [])
    {
        $error = new Error();
        $error->setCode(415)
            ->setMessage($msg)
            ->setError("Type de média non supporté")
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
