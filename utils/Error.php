<?php

namespace Utils;

use Exception;

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



    public static function joinErrorLogs($array)
    {
        $reduced = "\n" . array_reduce($array, function ($acc, $str) {
            return $acc . $str["label"] . ": " . json_encode($str["value"],  JSON_UNESCAPED_UNICODE) . "\n";
        }, "");
        return $reduced;
    }

    private function formatConsoleError()
    {
        $strs = [
            ["label" => "[LOCATION]", "value" => $this->location],
            ["label" => "[MESSAGE]", "value" => $this->message],
            ["label" => "[ERROR]", "value" => $this->error],
            ["label" => "[DATA]", "value" => $this->data],
            ["label" => "[CODE]", "value" => $this->code]
        ];

        $log = self::joinErrorLogs($strs);

        return $log;
    }

    public function sendAndDie()
    {
        error_log($this->formatConsoleError());
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
