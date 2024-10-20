<?php

namespace Controller;

require_once '../../Autoloader.php';

use Closure;
use Utils\Error;
use DTO\Request;
use DTO\Response;
use Controller\ControllerInterface;
use Model\Schema\Schema;


/**
 * 
 * class Controller
 * 
 * @property Error $error
 * @property Response $response
 * @property Request $request
 * @property Schema $schema
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
    protected ?Schema $data_parsed = null;

    public function __construct(array $methods, Request $request, Response $response, Schema $schema = null)
    {
        try {
            $request->setAuthorizedMethods($methods);
            $response->setMethods($methods);


            $method_not_authorized = $request->is_methods_not_authorized();
            $endpoint = $request->getEndpoint();

            $this->error = new Error();
            if ($method_not_authorized) {
                $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $methods);
                throw $this->error
                    ->setLocation($endpoint)
                    ->setError("Méthode non autorisée.")
                    ->setCode(405)
                    ->setMessage($error_message);
            }

            if ($schema) {
                $data = $request->getClientDecodedData();
                $this->data_parsed = $schema->safeParse($data);
            }

            $this->request = $request;
            $this->response = $response;
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }

    public function handleRequest(callable $anonymous_handler): void
    {
        try {
            $binded_handler = Closure::bind($anonymous_handler, $this, get_class($this));
            $response_data = $binded_handler();
            $this->response
                ->setData($response_data)
                ->sendAndDie();
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }
}
