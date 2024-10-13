<?php

namespace Utils;

class Response
{
    private $code = 0;
    private $data = [];
    private $message = null;
    private $error = null;
    private $headers = [];

    public function __construct($code = 0, $data = [], $message = null, $error = null, $headers = [])
    {
        $this->code = $code;
        $this->data = $data;
        $this->message = $message;
        $this->error = $error;
        $this->headers = $headers;
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

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;

    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the headers for the response.
     */
    private function attachHeaders()
    {
        foreach ($this->headers as $header) {
            header($header);
        }
        return $this;
    }

    public function sendAndDie()
    {
        if($this->code === 0) {
            die();
        }
        $this->attachHeaders();
        http_response_code($this->code);
        echo json_encode(["message" => $this->message, "data" => $this->data, "error" => $this->error]);
        die();
    }
}
