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
 * 
 */
class Request
{

    // Request related properties

    private string $request_method = "";

    // Body request data related properties

    private string $client_raw_json = "";
    private array $client_decoded_data = [];
    private bool $has_data = false;
    private bool $is_valid_json = true;
    private string $json_error_msg = "";

    private bool $has_form_data = false;

    // Query related properties

    private bool $has_query = false;
    private array $query_params = [];


    public function __construct()
    {
        try {

            // Set the request method from server global variable
            $this->request_method = $_SERVER["REQUEST_METHOD"] ?? "";

            // Set the query parameters from the server global variable
            parse_str($_SERVER["QUERY_STRING"], $this->query_params);

            // Query utility property for easy uses
            $this->has_query = !empty($this->query_params);

            // Get the client payload from body and store it for easy access
            $this->client_raw_json = file_get_contents("php://input") ?? "";

            // if the client send a form data ( of course using post method )
            if ($this->getRequestMethod() === "POST" && !empty($_POST)) {

                // utility property to check if the request has data in form 
                $this->has_form_data = true;

                // store the form data in a property
                $form_data = $_POST;

                // cast form_data in client_raw_json
                $this->client_raw_json = json_encode($form_data);

                // clone and store the form data in a property
                $this->client_decoded_data = [...$form_data];

                // now data from form or data from body can be accessed with the same method (getDecodedData)
            }


            // utility property to check if the request has data in body
            if (!empty($this->client_raw_json)) {
                $this->has_data = true;
            }

            // Decode the client data here with a pure function and destructuring the return 
            // safeDecode is safe and doesn't throw an error ( check is in middleware )
            if ($this->has_data)
                [$this->client_decoded_data, $this->is_valid_json, $this->json_error_msg] = self::safeDecode($this->client_raw_json);
        } catch (Error) {
            Error::HTTP500(`Une erreur interne s'est produite`, []);
        }
    }

    /**
     * decode the client data and return an array with the decoded data, a boolean if the json is valid and the error message
     */
    public static function safeDecode(string $json): array
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
        return [$decoded, !empty($decoded), $error];
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
