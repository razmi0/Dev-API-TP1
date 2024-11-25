<?php

namespace HTTP;

const PROJECT_ROOT = __DIR__ . "/../";

require_once PROJECT_ROOT . 'vendor/autoload.php';

use HTTP\Config\PayloadConfig;
use HTTP\Config\ResponseConfig;
use HTTP\Payload;
use Utils\Console;
use Utils\Patterns\SingletonAbstract;

interface IResponse
{
    public function setCode($code): self;
    public function setMessage($message): self;
    public function setPayload($data): self;
    public function setError(string $error): self;
    public function setContentType(string $content_type): self;
    public function setOrigin(string $origin): self;
    public function setMethods(array $methods): self;
    public function setAge($age): self;
    public function send();
}

/**
 * Class Response
 * 
 * This class is responsible for sending the response to the client
 * - **getInstance**: returns the instance of the class
 * - **setCode**: sets the response code
 * - **setMessage**: sets the message in the payload
 * - **setPayload**: sets the data in the payload
 * - **setError**: sets the error in the payload
 * - **setContentType**: sets the content type in the header
 * - **setOrigin**: sets the origin in the header
 * - **setMethods**: sets the methods in the header
 * - **setAge**: sets the age in the header
 * - **send**: sends the response to the client
 */
class Response extends SingletonAbstract implements IResponse
{
    private static ?Response $instance = null;
    private ?int $code = null;
    private array $header = [];
    private ?Payload $payload = null;
    private array $cookies = [];

    private function __construct(ResponseConfig $config)
    {
        $this->code = $config->get("code");

        $this->payload = new Payload(
            new PayloadConfig(
                $config->get("message"),
                $config->get("data"),
                $config->get("error")
            )
        );

        $this->header = [
            "Access-Control-Allow-Methods: " => self::methodsToString($config->get("methods")),
            "Content-Type: " => $config->get("content_type"),
            "Access-Control-Allow-Origin: " => $config->get("origin"),
            "Access-Control-Age: " => $config->get("age"),
            "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" => ""
        ];

        if ($config->get("location") !== null)
            $this->setLocation($config->get("location"));
    }

    public static function getInstance(ResponseConfig $config): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
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

    /**
     * takes an array of cookies and adds them to the response
     */
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
        $this->header["Content-Type: "] = $content_type;
        return $this;
    }

    public function setOrigin(string $origin): self
    {
        $this->header["Access-Control-Allow-Origin: "] = $origin;
        return $this;
    }

    public function setMethods(array $methods): self
    {
        $this->header["Access-Control-Allow-Methods: "] = self::methodsToString($methods);
        return $this;
    }

    public function setAge($age): self
    {
        $this->header["Access-Control-Age: "] = $age;
        return $this;
    }

    public function setLocation(string $location): self
    {
        $this->header["Location: "] = $location;
        return $this;
    }

    public function send()
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
