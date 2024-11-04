<?php

namespace HTTP;

require_once "../vendor/autoload.php";

use Exception;
use HTTP\Payload;
use Utils\Console;


class Response
{
    private const POSSIBLE_CODES = [200, 201, 202, 204, 400, 401, 403, 404, 405, 500];
    private const POSSIBLE_METHODS = ["GET", "POST", "PUT", "DELETE"];
    private ?int $code = null;

    private array $header = [];

    // Default payload
    private ?Payload $payload = null;

    // The constructor sets a default configuration for the response
    // Code and methods are required
    public function __construct($config)
    {

        // if no code or method header throw an exception
        // if code is not valid throw an exception
        // if methods are not valid throw an exception
        self::validateConfig($config);

        // valid config now
        $this->code = $config["code"];

        $this->payload = new Payload([
            "message" => $config["message"] ?? "",
            "data" => key_exists("data", $config) && is_array($config["data"]) ? $config["data"] : [],
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
    }

    private static function validateConfig(array $config): void
    {
        $mandatoryError = "Code and methods required in Response, received : ";
        $codeError = "Invalid response code, received : ";
        $methodsError = "Invalid methods, received : ";

        if (!self::checkMandatoryFields($config))
            throw new Exception($mandatoryError . "code : " .  $config["code"] . "methods :" . $config["methods"]); // not valid config
        if (!self::checkValidCode($config["code"]))
            throw new Exception($codeError . $config["code"]); // not valid code
        if (!self::checkValidMethods($config["header"]["methods"]))
            throw new Exception($methodsError . self::methodsToString($config["header"]["methods"]) . " expected : " . self::methodsToString(self::POSSIBLE_METHODS)); // not valid methods

    }

    private static function checkMandatoryFields(array $config): bool
    {
        return key_exists("code", $config) && key_exists("header", $config) && key_exists("methods", $config["header"]);
    }

    private static function checkValidMethods(array $methods): bool
    {
        return count(array_diff($methods, self::POSSIBLE_METHODS)) === 0;
    }

    private static function checkValidCode(int $code): bool
    {
        return in_array($code, self::POSSIBLE_CODES);
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

    public function setData($data): self
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

        var_export($this);

        // die();
    }
}


$response = new Response([
    "code" => 200,
    "error" => null,
    "message" => "Produit obtenu avec succés",
    "data" => [
        "produits" => [
            "id" => 200,
            "nom" => "Produit 1",
            "prix" => 1000,
            "quantite" => 10
        ]
    ],
    "header" => [
        "methods" => ["GET"],
    ]
]);

$response->sendAndDie();
