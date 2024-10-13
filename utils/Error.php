<?php

namespace Utils;
use Exception;

class Error extends Exception {
    protected $code = 0;
    protected $message = null;
    private $error = null;
    private $data = [];

    public function __construct($code = 0, $message = null, $error = null, $data = [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->error = $error;
        $this->data = $data;
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

    public function sendAndDie()
    {
        error_log("Message : " . $this->message . " Error : " . $this->error);
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($this->code);
        echo json_encode([
            "message" => $this->message,
            "data" => $this->data,
            "error" => $this->error
        ]);
        die();
    }
}