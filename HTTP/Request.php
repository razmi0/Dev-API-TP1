<?php

namespace HTTP;


use HTTP\Error;


/**
 * 
 * 
 * Class Request
 * 
 * @property string $request_method
 * 
 * @property array $authorized_methods
 * 
 * @property string $endpoint
 * 
 * @property string $client_raw_json
 * 
 * @property array $client_decoded_data
 * 
 * 
 * @method string getRequestMethod()
 * 
 * @method array getAuthorizedMethods()
 * 
 * @method string getEndpoint()
 * 
 * @method array getDecodedBody()
 * 
 * @method string getClientRawJson()
 * 
 * @method bool is_methods_not_authorized() 
 * 
 */
class Request
{

    // Request & Response related properties
    protected string $request_method = "";
    protected array $authorized_methods = [];

    // Client data related properties
    protected string $client_raw_json = "";
    protected array $client_decoded_data = [];
    protected bool $has_data = false;
    protected bool $valid_json = true;
    protected string $json_error_msg = "";

    // URI related properties
    // flags
    protected bool $has_scheme = false;
    protected bool $has_host = false;
    protected bool $has_port = false;
    protected bool $has_path = false;
    protected bool $has_query = false;

    // URI components
    protected ?string $scheme = "";
    protected ?string $host = "";
    protected ?string $port = "";
    protected ?string $path = "";

    // URI parameters
    protected array $query_params = [];

    private ?Error $error  = null;

    public function __construct(array $request)
    {
        $this->error = new Error();

        try {


            // Get the request method (GET, POST, PUT, DELETE, ...)
            $this->request_method = $_SERVER["REQUEST_METHOD"] ?? "";

            // Get the authorized methods (GET, POST, PUT, DELETE, ...)
            $this->authorized_methods = $request["methods"] ?? [];

            // Assign URI components to class properties
            // build the URI parameters
            [
                $this->scheme,
                $this->host,
                $this->port,
                $this->path,
                $this->query_params,
            ] = $this->buildURIParams();

            // build the URI flags
            [
                $this->has_scheme,
                $this->has_host,
                $this->has_port,
                $this->has_path,
                $this->has_query,
            ] = $this->buildURIFlags();

            // Get the raw client data and store it in the class property for easy access
            $this->client_raw_json = file_get_contents("php://input") ?? "";

            // Decode the client data for easy access too
            // if no data is present, the decoded data will be an empty array
            $this->client_decoded_data = $this->safeDecode($this->client_raw_json);
        } catch (Error) {
            throw $this->error->HTTP500("Une erreur interne s'est produite", [], "Request");
        }
    }

    /**
     * decode the client data setting the json error message if the json is invalid
     */
    private function safeDecode(string $json): array
    {
        // json => associative array
        $decoted = json_decode($json, true) ?? [];

        if (!json_last_error() === JSON_ERROR_NONE) {
            $this->json_error_msg = json_last_error_msg();
            $this->valid_json = false;
        }

        $this->has_data = !empty($decoted);
        return $decoted;
    }

    public function is_methods_not_authorized(): bool
    {
        return !in_array($this->request_method, $this->authorized_methods);
    }

    public function setAuthorizedMethods(array $methods)
    {
        $this->authorized_methods = $methods;
        return $this;
    }

    public function getAuthorizedMethods(): array
    {
        return $this->authorized_methods;
    }
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
    public function getDecodedBody(string $key = null): mixed
    {
        if ($key) {
            return $this->client_decoded_data[$key] ?? null;
        }
        return $this->client_decoded_data;
    }
    public function getIsValidJson(): bool
    {
        return $this->valid_json;
    }
    public function getJsonErrorMsg(): string
    {
        return $this->json_error_msg;
    }
    public function getClientRawJson(): string
    {
        return $this->client_raw_json;
    }
    public function getHasData(): bool
    {
        return $this->has_data;
    }

    private function buildURIParams(): array
    {
        parse_str($_SERVER["QUERY_STRING"], $output);
        return [ // http, https
            $_SERVER['REQUEST_SCHEME'],
            // localhost
            $_SERVER["HTTP_HOST"] ?? null,
            // 80
            $_SERVER['SERVER_PORT'] ?? null,
            // /api/v1/{endpoint}.php
            parse_url($_SERVER["REQUEST_URI"])["path"] ?? null,
            // ?param1=1?param2=2
            $output ?? null,
        ];
    }

    private function buildURIFlags(): array
    {
        $keys = ["scheme", "host", "port", "path", "query_params"];
        return array_map(fn($key) => isset($this->$key), $keys);
    }

    public function getQueryParam($key): string | null | array
    {
        return $this->query_params[$key] ?? "";
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFlag($flag): bool
    {
        return $this->$flag;
    }

    public function getURIComponent($key): string
    {
        return $this->$key;
    }
}
