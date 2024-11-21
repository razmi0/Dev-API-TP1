<?php

namespace HTTP;


use HTTP\Error;


/**
 * 
 * 
 * Class Request
 * 
 * very important class
 * 
 * @property string $request_method The request method
 * 
 * @property string $client_raw_json The raw body content of the request
 * @property array $client_decoded_data The decoded body content of the request
 * @property bool $has_data If the request has data
 * @property bool $is_valid_json If the request body is a valid json
 * @property string $json_error_msg The json error message
 * 
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

    public function __construct(
        // Request related properties
        private string $request_method = "",
        private array $headers = [],
        private array $cookies = [],
        private array $query_params = [],

        // Body request data related properties
        private string $client_raw_json = "",
        private array $client_decoded_data = [],
        private string $json_error_msg = "",

        // flags
        private bool $has_data = false,
        private bool $is_valid_json = false,
        private bool $has_form_data = false,
        private bool $has_body_data = false,
        private bool $has_query = false,
    ) {
        try {
            // Set the request method from server global variable
            $this->request_method = $_SERVER["REQUEST_METHOD"] ?? "";

            // Set the headers from the server global variable
            $this->headers = getallheaders();

            // Set the cookies from the server global variable
            $this->cookies = $_COOKIE;

            // Get the client json from body and store it for easy access
            // POST, PUT, PATCH, DELETE, GET ect.. raw data are stored here by PHP
            $this->client_raw_json = file_get_contents("php://input") ?? "";

            // Utility property to check if the request has data
            $this->has_data = !empty($this->client_raw_json);

            // Set the query parameters from the server global variable
            parse_str($_SERVER["QUERY_STRING"], $this->query_params);

            // Query utility property for easy uses
            $this->has_query = !empty($this->query_params);


            if (isset($_SERVER["CONTENT_TYPE"])) {

                // switch case syntax
                switch ($this->headers["Content-Type"]) {
                    case "application/x-www-form-urlencoded":
                        // data come from a form
                        parse_str($this->client_raw_json, $this->client_decoded_data);

                        //                          not empty
                        $this->has_form_data = !empty($this->client_decoded_data);
                        break;

                    case "application/json":
                        // data come from the body of the request
                        [$this->client_decoded_data, $this->is_valid_json, $this->json_error_msg] = self::safeDecode($this->client_raw_json);

                        //                              valid json and not empty
                        $this->has_body_data = $this->is_valid_json && !empty($this->client_decoded_data);
                        break;

                    default:
                        // unsupported media type
                        Error::HTTP415("Unsupported Media Type : " . $this->headers["Content-Type"]);
                        break;
                }
            }
        } catch (Error) {
            Error::HTTP500(`Une erreur interne s'est produite`, []);
        }
    }

    /**
     * decode the client data and return an array with the decoded data,
     * a boolean if the json is valid and the error message
     * does not throw an exception (safe)
     */
    private static function safeDecode(string $json): array
    {
        $error = "";

        // json to associative array or empty array
        $decoded = json_decode($json, true) ?? [];

        // if not valid json
        if (json_last_error() !== JSON_ERROR_NONE) {

            // store the error message
            $error = "[JSON ERROR] : " . json_last_error_msg();
        }

        //      [data,       isValid,       error]
        return [$decoded, $decoded !== NULL, $error];
    }

    public function getHeader(string $key): string | false
    {
        return $this->headers[$key] ?? false;
    }

    public function getDecodedData(string $key = null): mixed
    {
        if ($key) {
            return $this->client_decoded_data[$key] ?? null;
        }
        return $this->client_decoded_data;
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

    public function getQueryParam($key): string | null | array
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
