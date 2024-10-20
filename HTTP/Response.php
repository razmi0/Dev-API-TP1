<?php

namespace HTTP;


class Response
{
    private int $code = 0;
    private ?array $data = [];
    private string $message = "";
    private string $error = "";
    private string $content_type = "application/json";
    private string  $origin = "*";
    private array $methods = ["GET"];
    private int $age = 3600;


    public function __construct($response)
    {
        $this->code = $response["code"];
        $this->message = $response["message"];
        $this->content_type = $response["headers"]["content_type"] ?? "application/json";
        $this->origin = $response["headers"]["origin"] ?? "*";
        $this->methods = $response["headers"]["methods"] ?? ["GET"];
        $this->age = $response["headers"]["age"] ?? 3600;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function setContentType($content_type)
    {
        $this->content_type = $content_type;
        return $this;
    }

    public function setOrigin($origin)
    {
        $this->origin = $origin;
        return $this;
    }

    public function setMethods($methods)
    {
        $this->methods = $methods;
        return $this;
    }

    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    public function sendAndDie()
    {
        // No response object instanciated so we die
        if ($this->code === 0) {
            die();
        }
        header("Content-Type: " . $this->content_type);
        header("Access-Control-Allow-Origin: " . $this->origin);
        header("Access-Control-Allow-Methods: " . implode(", ", $this->methods));
        header("Access-Control-Age: " . $this->age);
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        http_response_code($this->code);

        echo json_encode(["message" => $this->message, "data" => $this->data ?? [], "error" => $this->error]);
        die();
    }
}
