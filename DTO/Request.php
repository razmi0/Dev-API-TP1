<?php

namespace DTO;



/**
 * Class Request
 * 
 * @property string $request_method
 * @property array $authorized_methods
 * @property string $endpoint
 * 
 * @method string getRequestMethod()
 * @method array getAuthorizedMethods()
 * @method string getEndpoint()
 * 
 */
class Request
{


    private string $request_method = "";
    private array $authorized_methods = [];
    private string $endpoint = "";

    public function __construct(array $request)
    {

        $this->authorized_methods = $request["methods"];
        $this->request_method = $_SERVER["REQUEST_METHOD"];
        $this->endpoint = $request["endpoint"];
    }

    public function isMethodAuthorized(): bool
    {
        return in_array($this->request_method, $this->authorized_methods);
    }

    public function getRequestMethod(): string
    {
        return $this->request_method;
    }

    public function getAuthorizedMethods(): array
    {
        return $this->authorized_methods;
    }
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
}
