<?php

namespace Controller;

require_once '../../Autoloader.php';

use Closure;
use Utils\Error;
use DTO\Request;
use DTO\Response;
use Controller\ControllerInterface;


/**
 * 
 * class Controller
 * 
 * @property Error $error
 * @property Response $response
 * @property Request $request
 * @property array $client_decoded_data
 * @property string $client_raw_json
 * 
 * @method __construct(Request $request, Schema $schema, Response $response)
 * @method handleRequest(callable $handler): void
 * 
 */
class Controller implements ControllerInterface
{
    protected ?Error $error = null;
    protected ?Response $response = null;
    protected ?Request $request = null;
    protected array $client_decoded_data = [];
    protected string $client_raw_json = "";

    public function __construct(Request $request, Response $response)
    {
        try {
            $this->error = new Error();
            $this->error->setLocation($request->getEndpoint());

            if (!$request->isMethodAuthorized()) {
                $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $request->getAuthorizedMethods());
                throw $this->error
                    ->setError("Méthode non autorisée.")
                    ->setCode(405)
                    ->setMessage($error_message);
            }
            $this->request = $request;
            $this->response = $response;

            $this->client_raw_json = file_get_contents("php://input");
            $this->client_decoded_data = json_decode($this->client_raw_json, true);
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }

    public function handleRequest(callable $anonymous_handler): void
    {
        try {
            $binded_handler = Closure::bind($anonymous_handler, $this, get_class($this));
            $this->response
                ->setData($binded_handler())
                ->sendAndDie();
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }
}
