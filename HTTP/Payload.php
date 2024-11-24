<?php

namespace HTTP;

const PROJECT_ROOT = __DIR__ . "/../";

require_once PROJECT_ROOT . 'vendor/autoload.php';

class Payload
{
    private string $message = "";
    private array $data = [];
    private string $error = "";

    public function __construct(array $config)
    {
        $this->message = $config["message"] ?? "";
        $this->data = $config["data"] ?? [];
        $this->error = $config["error"] ?? "";
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    public function toJson(): string
    {
        return json_encode([
            "message" => $this->message,
            "data" => $this->data,
            "error" => $this->error
        ]);
    }

    public function toArray(): array
    {
        return [
            "message" => $this->message,
            "data" => $this->data,
            "error" => $this->error
        ];
    }
}
