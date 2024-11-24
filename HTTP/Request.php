<?php

namespace HTTP;

use HTTP\Error;

/**
 * Class Request
 * 
 * @property string $request_method The request method
 * @property string $client_raw_json The raw body content of the request
 * @property array $client_decoded_data The decoded body content of the request
 * @property bool $has_data If the request has data
 * @property bool $is_valid_json If the request body is a valid json
 * @property string $json_error_msg The json error message
 * @property bool $has_query If the request has query parameters
 * @property array $query_params The query parameters
 * 
 * @method getRequestMethod()
 * @method getDecodedData()
 * @method getIsValidJson()
 * @method getJsonErrorMsg()
 * @method getHasData()
 * @method getQueryParam()
 * @method getHeader()
 */
class Request
{
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

    public function __construct()
    {
        try {
            $this->initializeRequest();
        } catch (Error $e) {
            Error::HTTP500("Une erreur interne s'est produite", []);
        }
    }

    private function initializeRequest(): void
    {
        $this->request_method = $_SERVER["REQUEST_METHOD"] ?? "";
        $this->domain = $_SERVER["HTTP_HOST"] ?? "";
        $this->headers = getallheaders();
        $this->cookies = $_COOKIE;
        $this->client_raw_json = file_get_contents("php://input") ?? "";
        $this->has_data = !empty($this->client_raw_json);
        parse_str($_SERVER["QUERY_STRING"], $this->query_params);
        $this->has_query = !empty($this->query_params);

        if (isset($_SERVER["CONTENT_TYPE"])) {
            $this->parseRequestBody();
        }
    }

    private function parseRequestBody(): void
    {
        switch ($this->headers["Content-Type"]) {
            case "application/x-www-form-urlencoded":
                parse_str($this->client_raw_json, $this->client_decoded_data);
                $this->has_form_data = !empty($this->client_decoded_data);
                break;

            case "application/json":
                [$this->client_decoded_data, $this->is_valid_json, $this->json_error_msg] = self::safeDecode($this->client_raw_json);
                $this->has_body_data = $this->is_valid_json && !empty($this->client_decoded_data);
                break;

            default:
                Error::HTTP415("Unsupported Media Type: " . $this->headers["Content-Type"]);
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
