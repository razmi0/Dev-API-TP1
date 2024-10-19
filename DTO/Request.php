<?php

namespace DTO;



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
 * @method array getClientDecodedData()
 * 
 * @method string getClientRawJson()
 * 
 * 
 */
class Request
{

    protected string $request_method = "";
    protected array $authorized_methods = [];
    protected string $endpoint = "";
    protected string $client_raw_json = "";
    protected array $client_decoded_data = [];

    public function __construct(array $request)
    {
        $this->authorized_methods = $request["methods"];
        $this->request_method = $_SERVER["REQUEST_METHOD"];
        $this->endpoint = $request["endpoint"];
        $this->client_raw_json = file_get_contents("php://input");
        $this->client_decoded_data = json_decode($this->client_raw_json, true);
    }

    public function isMethodAuthorized(): bool
    {
        return in_array($this->request_method, $this->authorized_methods);
    }

    public function getAuthorizedMethods(): array
    {
        return $this->authorized_methods;
    }
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
    public function getClientDecodedData(): array
    {
        return $this->client_decoded_data;
    }
    public function getClientRawJson(): string
    {
        return $this->client_raw_json;
    }
}
