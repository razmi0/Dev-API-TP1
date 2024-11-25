<?php

namespace HTTP;

use Exception;
use HTTP\Error;
use Utils\Console;
use Utils\Patterns\{SingletonAbstract, ISingleton};

interface IRequest extends ISingleton
{
    public function getRequestMethod(): string;
    public function getDecodedData(): mixed;
    public function getIsValidJson(): bool;
    public function getJsonErrorMsg(): string;
    public function getHasData(): bool;
    public function getQueryParam($key): string|null|array;
    public function getHeader(string $key): string|false;
}

/**
 * Class Request
 * 
 * This class is a singleton that handles the request data
 * - **getInstance()** returns the instance of the class
 * - **addAttribute()** adds an attribute to the request
 * - **getAttribute()** gets an attribute from the request
 * - **getHeader()** gets a header from the request
 * - **getDomain()** gets the domain from the request
 * - **getCookie()** gets a cookie from the request
 * - **getDecodedData()** gets the decoded data from the request
 * - **setDecodedBody()** sets the decoded data from the request
 * - **getHasFormData()** checks if the request has form data
 * - **getIsValidJson()** checks if the request has a valid JSON
 * - **getJsonErrorMsg()** gets the JSON error message
 * - **getHasData()** checks if the request has data
 * - **getQueryParam()** gets a query parameter from the request
 * - **getRequestMethod()** gets the request method
 */
class Request extends SingletonAbstract implements IRequest
{
    private static ?Request $instance = null;
    private string $request_method = "";
    private array $headers = [];
    private array $cookies = [];
    private array $query_params = [];
    private string $domain = "";
    private array $attributes = [];
    private string $client_raw_json = "";
    private array $client_decoded_data = [];
    private string $json_error_msg = "";
    private bool $has_data = false;
    private bool $is_valid_json = false;
    private bool $has_form_data = false;
    private bool $has_body_data = false;
    private bool $has_query = false;

    private function __construct()
    {
        try {
            $this->initializeRequest();
        } catch (Exception $e) {
            Console::log($e->getMessage(), "IN REQUEST CLASS", [], 500);
            Error::HTTP500("Une erreur interne s'est produite", []);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initializeRequest(): void
    {
        $this->request_method = $_SERVER["REQUEST_METHOD"] ?? "";                  // Set request method
        $this->domain = $_SERVER["HTTP_HOST"] ?? "";                               // Set request domain
        $this->headers = getallheaders();                                          // Get all headers
        $this->cookies = $_COOKIE;                                                 // Get all cookies
        $this->client_raw_json = file_get_contents("php://input") ?? "";           // Get raw input
        $this->has_data = !empty($this->client_raw_json);                          // Check if has data
        parse_str($_SERVER["QUERY_STRING"], $this->query_params);                  // Parse query string
        $this->has_query = !empty($this->query_params);                            // Check if has query

        isset($_SERVER["CONTENT_TYPE"])
            ? $this->parseRequestBody()
            : Error::HTTP415("Please provide a Content-Type header : application/x-www-form-urlencoded OR application/json");
    }

    private function parseRequestBody(): void
    {
        switch ($this->headers["Content-Type"]) {                                                           // Check content type
            case "application/x-www-form-urlencoded":                                                       // Handle form data
                parse_str($this->client_raw_json, $this->client_decoded_data);                              // Parse form data
                $this->has_form_data = !empty($this->client_decoded_data);                                  // Check if form data
                break;

            case "application/json":                                                                        // Handle JSON data
                [
                    $this->client_decoded_data,
                    $this->is_valid_json,
                    $this->json_error_msg
                ] = self::safeDecode($this->client_raw_json);                                               // Decode JSON
                $this->has_body_data = $this->is_valid_json && !empty($this->client_decoded_data);          // Check if valid JSON
                break;

            default:                                                                                        // Handle unsupported type
                Error::HTTP415("Unsupported Media Type: " . $this->headers["Content-Type"]);                // Throw error
                break;
        }
    }

    private static function safeDecode(string $json): array
    {
        $decoded = json_decode($json, true) ?? [];
        $error = json_last_error() !== JSON_ERROR_NONE ? "[JSON ERROR]: " . json_last_error_msg() : "";
        return [$decoded, $decoded !== null, $error];
    }

    public function addAttribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function getHeader(string $key): string|false
    {
        return $this->headers[$key] ?? false;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getCookie(string $key): string|false
    {
        return $this->cookies[$key] ?? false;
    }

    public function getDecodedData(string $key = null): mixed
    {
        return $key ? ($this->client_decoded_data[$key] ?? null) : $this->client_decoded_data;
    }

    public function setDecodedBody(array $data): self
    {
        $this->client_decoded_data = $data;
        return $this;
    }

    public function getHasFormData(): bool
    {
        return $this->has_form_data;
    }

    public function getIsValidJson(): bool
    {
        return $this->is_valid_json;
    }

    public function getJsonErrorMsg(): string
    {
        return $this->json_error_msg;
    }

    public function getHasData(): bool
    {
        return $this->has_data;
    }

    public function getQueryParam($key): string|null|array
    {
        return $this->query_params[$key] ?? "";
    }

    public function getRequestMethod(): string
    {
        return $this->request_method;
    }

    public function getHasQuery(): bool
    {
        return $this->has_query;
    }
}
