<?php

namespace Utils;

class Response
{
    private $code = 0;
    private $message = "";
    private $data = [];
    private $headers = [];

    public function __construct($code, $message, $data, $headers)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $this->headers = $headers;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }

     /**
     * Sets the headers for the response.
     */
    public function attachHeaders()
    {
        foreach ($this->headers as $header) {
            header($header);
        }
        return $this;
        // header("Content-Type: application/json");
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Methods: POST");
        // header("Access-Control-Age: 3600");
        // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public function send()
    {
        http_response_code($this->code);
        echo json_encode(["message" => $this->message, "data" => $this->data]);
        die();
    }
}
