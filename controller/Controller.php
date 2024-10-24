<?php

namespace Controller;

require_once '../../Autoloader.php';

use Closure;
use Utils\Error;
use HTTP\Request;
use HTTP\Response;
use Controller\ControllerInterface;
use Model\Dao\ProduitDao;
use Model\Schema\Schema;

/**
 *  _____ _______ _______ _______ _______ _______ _______ _______
 * |                                                             |
 * |                     |
 * |_____________________________________________________________|
 * 
 */

/**
 * 
 * class Controller
 * 
 * @property Error $error
 * @property Response $response
 * @property Request $request
 * @property Schema $schema
 * @property Error $error
 * 
 * @method __construct(Request $request, Schema $schema, Response $response) Dependency injection
 * @method handleRequest(callable $handler): void
 * 
 */
class Controller implements ControllerInterface
{
    protected ?Error $error = null;
    protected ?Response $response = null;
    protected ?Request $request = null;
    protected ?Schema $schema = null;

    public function __construct(array $methods, Request $request, Response $response, Error $error, Schema $schema = null)
    {
        try {
            $this->request = $request;
            $this->response = $response;
            $this->error = $error;
            $this->schema = $schema;

            // We set the authorized methods for the request
            $request->setAuthorizedMethods($methods);

            // We set the methods for the response header
            $response->setMethods($methods);

            // We get the endpoint
            $endpoint = $request->getEndpoint();

            // We set the endpoint for the debugging purpose
            $this->error->setLocation($endpoint);

            // Middleware 1
            // We check if the method is authorized
            // If the method is not authorized, we throw an error
            if ($request->is_methods_not_authorized()) {
                $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $methods);
                throw $this->error
                    ->setLocation($endpoint)
                    ->setError("Méthode non autorisée.")
                    ->setCode(405)
                    ->setMessage($error_message);
            }

            // Middleware 2
            // We check if the client data is a valid JSON
            // If the data is not a valid JSON, we throw an error with the JSON error message
            if (!$request->getIsValidJson()) {
                $error_message = $request->getJsonErrorMsg();
                throw $this->error
                    ->setLocation($endpoint)
                    ->setError("Données invalides.")
                    ->setCode(400)
                    ->setMessage("Les données envoyées ne sont pas valides : " . $error_message);
            }
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }

    public function handleRequest(callable $anonymous_handler): void
    {
        try {
            //
            // Ici ce joue l'externalisation de la méthode handleRequest ( polymorphisme ++ wouaw )
            // => On crée une closure dans laquelle on bind à son contexte d'execution l'instance $this de Controller
            // $this contenant les propriétés de la classe Controller/Request/Response/Schema,
            // la fonction $anonymous_handler peut donc accéder à ces propriétés
            // 
            $binded_handler = Closure::bind(
                /**
                 * la closure $anonymous_handler executant le code du endpoint
                 */
                $anonymous_handler,
                /**
                 * $this est le newThis de la closure $anonymous_handler
                 */
                $this,
                /**
                 * la classe Controller est le nouveau scope de la closure $anonymous_handler
                 */
                self::class
            );

            // We retrieve the response data from the handler
            $response_data = $binded_handler();

            // We send the response data to the client
            $this->response
                ->setData($response_data)
                ->sendAndDie();
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }
}
