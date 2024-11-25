<?php

namespace HTTP\Config;

use HTTP\Config\IConfig;
use HTTP\Config\PayloadConfig;

/**
 * PayloadConfig class
 * 
 * This class give type hinting to your IDE for the response configuration
 * - **__construct**: constructor($message, $data, $error, $code, $methods, $content_type, $origin, $age, $location)
 */
class ResponseConfig extends PayloadConfig implements IConfig
{
    private array $configs = [];

    public function __construct(
        string $message = "",
        array $data = [],
        string $error = "",
        int $code = 0,
        array $methods = ["GET"],
        string $content_type = "application/json",
        string $origin = "*",
        int $age = 3600,
        string $location = null
    ) {
        $this->configs = [
            "message" => $message,
            "data" => $data,
            "error" => $error,

            "code" => $code,
            "methods" => $methods,
            "content_type" => $content_type,
            "origin" => $origin,
            "age" => $age,
            "location" => $location,
        ];
    }

    public function get(string $key)
    {
        return $this->configs[$key];
    }

    public function set(string $key, mixed $value)
    {
        $this->configs[$key] = $value;
    }
}
