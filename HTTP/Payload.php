<?php

namespace HTTP;

use HTTP\Config\PayloadConfig;

require_once PROJECT_ROOT . 'vendor/autoload.php';

interface IPayload
{
    public function getMessage(): string;
    public function getData(): array;
    public function getError(): string;
    public function setMessage(string $message): self;
    public function setData(array $data): self;
    public function setError(string $error): self;
    public function toJson(): string;
    public function toArray(): array;
}


/**
 * Payload class
 * 
 * This class is used to send a response to the client
 * - **getMessage**: returns the message
 * - **getData**: returns the data
 * - **getError**: returns the error
 * - **setMessage**: sets the message
 * - **setData**: sets the data
 * - **setError**: sets the error
 * - **toJson**: returns the payload as a JSON string
 * - **toArray**: returns the payload as an array
 * - **__construct**: constructor($config)
 */
class Payload implements IPayload
{
    public function __construct(private PayloadConfig $config) {}

    public function getMessage(): string
    {
        return $this->config->get("message");
    }

    public function getData(): array
    {
        return $this->config->get("data");
    }

    public function getError(): string
    {
        return $this->config->get("error");
    }

    public function setMessage(string $message): self
    {
        $this->config->set("message", $message);
        return $this;
    }

    public function setData(array $data): self
    {
        $this->config->set("data", $data);
        return $this;
    }

    public function setError(string $error): self
    {
        $this->config->set("error", $error);
        return $this;
    }

    public function toJson(): string
    {
        return json_encode([
            "message" => $this->config->get("message"),
            "data" => $this->config->get("data"),
            "error" => $this->config->get("error")
        ]);
    }

    public function toArray(): array
    {
        return [
            "message" => $this->config->get("message"),
            "data" => $this->config->get("data"),
            "error" => $this->config->get("error")
        ];
    }
}
