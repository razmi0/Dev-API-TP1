<?php

namespace HTTP;

const PROJECT_ROOT = __DIR__ . "/../";

require_once PROJECT_ROOT . 'vendor/autoload.php';

use HTTP\Payload;
use Utils\Console;

class Response
{
    private ?int $code = null;
    private array $header = [];
    private ?Payload $payload = null;
    private array $cookies = [];

    public function __construct($config)
    {
        $this->code = $config["code"];
        $this->payload = new Payload([
            "message" => $config["message"] ?? "",
            "data" =>  $config["data"] ?? [],
            "error" => $config["error"] ?? ""
        ]);

        $this->header = [
            "Access-Control-Allow-Methods: " => self::methodsToString($config["header"]["methods"]),
            "Content-Type: " => $config["header"]["content_type"] ?? "application/json",
            "Access-Control-Allow-Origin: " => $config["header"]["origin"] ?? "*",
            "Access-Control-Age: " => $config["header"]["age"] ?? 3600,
            "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" => ""
        ];

        if (isset($config["header"]["location"])) {
            $this->header = [...$this->header, "Location: " => $config["header"]["location"]];
        }
    }

    private static function methodsToString(array $methods): string
    {
        return implode(", ", $methods);
    }

    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }

    public function addCookies(array $cookies): self
    {
        $this->cookies[] = $cookies;
        return $this;
    }

    public function setMessage($message): self
    {
        $this->payload->setMessage($message);
        return $this;
    }

    public function setPayload($data): self
    {
        $this->payload->setData($data);
        return $this;
    }

    public function setError(string $error): self
    {
        $this->payload->setError($error);
        return $this;
    }

    public function setContentType(string $content_type): self
    {
        $this->header = [...$this->header, "content_type" => $content_type];
        return $this;
    }

    public function setOrigin(string $origin): self
    {
        $this->header = [...$this->header, "origin" => $origin];
        return $this;
    }

    public function setMethods(array $methods): self
    {
        $this->header = [...$this->header, "methods" => self::methodsToString($methods)];
        return $this;
    }

    public function setAge($age): self
    {
        $this->header = [...$this->header, "age" => $age];
        return $this;
    }

    public function sendAndDie()
    {
        foreach ($this->cookies as $cookie) {
            [$name, $value, $options] = $cookie;
            setcookie($name, $value, $options);
        }

        foreach ($this->header as $key => $value) {
            header($key . $value);
        }

        Console::log(
            $this->payload->getMessage(),
            $this->payload->getError(),
            $this->payload->getData(),
            $this->code
        );

        http_response_code($this->code);
        echo $this->payload->toJson();
    }
}
