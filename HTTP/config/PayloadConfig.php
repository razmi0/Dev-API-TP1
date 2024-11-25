<?php

namespace HTTP\Config;

use HTTP\Config\IConfig;

/**
 * PayloadConfig class
 * 
 * This class give type hinting to your IDE for the payload configuration
 * - **__construct**: constructor($message, $data, $error)
 */
class PayloadConfig implements IConfig
{
    private array $configs = [];

    public function __construct(string $message, array $data, string $error)
    {
        $this->configs = [
            "message" => $message,
            "data" => $data,
            "error" => $error
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
