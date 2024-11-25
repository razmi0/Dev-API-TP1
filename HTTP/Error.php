<?php

namespace HTTP;

const PROJECT_ROOT = __DIR__ . "/../";

require_once PROJECT_ROOT . 'vendor/autoload.php';

use HTTP\Config\PayloadConfig;
use Utils\Console;
use HTTP\Payload;

interface IError
{
    public static function HTTP404(string $msg, array $payload); // 404 Not found
    public static function HTTP405(string $msg, array $payload); // 405 Method not allowed
    public static function HTTP400(string $msg, array $payload); // 400 Bad request
    public static function HTTP401(string $msg, array $payload); // 401 Unauthorized
    public static function HTTP415(string $msg, array $payload); // 415 Unsupported Media Type
    public static function HTTP500(string $msg, array $payload); // 500 Internal server error
    public static function HTTP204(string $msg, array $payload); // 204 No content
    public static function HTTP503(string $msg, array $payload); // 503 Service unavailable
}

/**
 * Error class
 * 
 * Send an error response to the client
 * @static **HTTP404**: Not found
 * @static **HTTP405**: Method not allowed
 * @static **HTTP400**: Bad request
 * @static **HTTP401**: Unauthorized
 * @static **HTTP415**: Unsupported Media Type
 * @static **HTTP500**: Internal server error
 * @static **HTTP204**: No content
 * @static **HTTP503**: Service unavailable
 */
class Error implements IError
{
    private $code = 0;
    private $message = null;
    private $error = null;
    private $data = [];

    private function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    private function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    private function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    private function setPayload($data)
    {
        $this->data = $data;
        return $this;
    }

    private function send()
    {
        Console::log($this->message, $this->error, $this->data, $this->code);
        header("Content-Type: application/json; charset=UTF-8");

        $payloadConfig = new PayloadConfig(
            $this->message ?? "",
            $this->data ?? [],
            $this->error ?? "",
        );

        $payload = new Payload($payloadConfig);

        http_response_code($this->code);
        echo $payload->toJson();
        die();
    }

    public static function HTTP404(string $msg, array $payload = [])
    {
        (new self())->setCode(404)
            ->setMessage($msg)
            ->setError("Ressource non trouvée")
            ->setPayload($payload)
            ->send();
    }

    public static function HTTP405(string $msg, array $payload = [])
    {
        (new self())->setCode(405)
            ->setMessage($msg)
            ->setError("Méthode non autorisée")
            ->setPayload($payload)
            ->send();
    }

    public static function HTTP400(string $msg, array $payload = [])
    {
        (new self())->setCode(400)
            ->setMessage($msg)
            ->setError("Requête invalide")
            ->setPayload($payload)
            ->send();
    }

    public static function HTTP401(string $msg, array $payload = [])
    {
        (new self())->setCode(401)
            ->setMessage($msg)
            ->setError("Non autorisé")
            ->setPayload($payload)
            ->send();
    }

    public static function HTTP415(string $msg, array $payload = [])
    {
        (new self())->setCode(415)
            ->setMessage($msg)
            ->setError("Type de média non supporté")
            ->setPayload($payload)
            ->send();
    }

    public static function HTTP500(string $msg, array $payload = [])
    {
        (new self())->setCode(500)
            ->setMessage($msg)
            ->setError("Erreur interne")
            ->setPayload($payload)
            ->send();
    }

    public static function HTTP204(string $msg, array $payload = [])
    {
        (new self())->setCode(204)
            ->setMessage($msg)
            ->setError("Aucun contenu")
            ->setPayload($payload)
            ->send();
    }

    public static function HTTP503(string $msg, array $payload = [])
    {
        (new self())->setCode(503)
            ->setMessage($msg)
            ->setError("Service non disponible")
            ->setPayload($payload)
            ->send();
    }
}
