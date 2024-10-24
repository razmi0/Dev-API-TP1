<?php

namespace HTTP;

use Exception;
use Utils\Console;

/**
 * Class Error
 * 
 * 
 */
class Error extends Exception
{
    protected $code = 0;
    protected $message = null;
    private $error = null;
    private $data = [];
    private $location = null;

    public function __construct($code = 0, $message = null, $error = null, $data = [], $location = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->error = $error;
        $this->data = $data;
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }



    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function sendAndDie()
    {
        Console::log($this->location, $this->message, $this->error, $this->data, $this->code);
        header("Content-Type: application/json; charset=UTF-8");

        $payload = json_encode([
            "message" => $this->message,
            "data" => $this->data,
            "error" => $this->error
        ]);
        http_response_code($this->code);
        echo $payload;
        die();
    }
}
