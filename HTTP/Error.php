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
        Console::log($this->message, $this->error, $this->data, $this->code);
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

    /**
     * HTTP404
     * 
     * 404 Not found error 
     * 
     */
    public static function HTTP404(string $msg, array $payload = [], string $location = null)
    {
        $error = new Error();
        $error->setCode(404)
            ->setMessage($msg)
            ->setError("Page non trouvée")
            ->setData($payload)
            ->sendAndDie();
    }

    /**
     * HTTP405
     * 
     * 405 Method not allowed error
     * 
     */
    public static function HTTP405(string $msg, array $payload = [], string $location = null)
    {
        $error = new Error();
        $error->setCode(405)
            ->setMessage($msg)
            ->setError("Méthode non autorisée")
            ->setData($payload)
            ->sendAndDie();
    }

    /**
     * HTTP400
     * 
     * 400 Bad request error
     * 
     */
    public static function HTTP400(string $msg, array $payload = [], string $location = null)
    {
        $error = new Error();
        $error->setCode(400)
            ->setMessage($msg)
            ->setError("Requête invalide")
            ->setData($payload)
            ->sendAndDie();
    }

    /**
     * HTTP500
     * 
     * 500 Internal server error
     * 
     */
    public static function HTTP500(string $msg, array $payload = [], string $location = null)
    {
        $error = new Error();
        $error->setCode(500)
            ->setMessage($msg)
            ->setError("Erreur interne")
            ->setData($payload)
            ->sendAndDie();
    }

    /**
     * HTTP204
     * 
     * 204 No content error (no data to return)
     * 
     */
    public static function HTTP204(string $msg, array $payload = [], string $location = null)
    {
        $error = new Error();
        $error->setCode(204)
            ->setMessage($msg)
            ->setError("Aucun contenu")
            ->setData($payload)
            ->sendAndDie();
    }

    /**
     * HTTP503
     * 
     * 503 Service unavailable error
     * 
     */
    public static function HTTP503(string $msg, array $payload = [], string $location = null)
    {
        $error = new Error();
        $error->setCode(503)
            ->setMessage($msg)
            ->setError("Service non disponible")
            ->setData($payload)
            ->sendAndDie();
    }
}
