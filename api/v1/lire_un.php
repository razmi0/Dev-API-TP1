<?php

require_once "../../Autoloader.php";

use Model\Dao\ProduitDao as ProduitDao;
use Utils\Response as Response;
use Utils\Error as Error;

// Instantiate the controller
// --
$controller = new Controller();

// Handle the request (verify the method and process the request)
// If the method is allowed, the DAO method "create" is called and the request is processed.
// Else, an error message is returned.
// --
$controller->handleRequest();

// Handle the response (set the headers and send the response) using the Response class
// --
$controller->handleResponse();


class Controller
{
    private $produitDao = null;
    private $response = null;
    private $message = "";
    private $data = [];
    private $code = 0;

    public function __construct()
    {
        $this->produitDao = new ProduitDao();
        $this->response = new Response();
    }

    public function handleRequest()
    {
        switch ($_SERVER["REQUEST_METHOD"]) {

                /**
             * GET request : /api/v1/lire_un.php
             * Seulement les requêtes GET sont autorisées.
             */

            case "GET":
                // We get all the parameters from the URI ( path and query )
                // --
                $urlParams = parse_url($_SERVER["REQUEST_URI"]);

                // We check if the query is present in the URI
                // --
                $hasQuery = isset($urlParams["query"]);

                // We get the id from the query or the body of the request
                // If no id is found, id will be null
                // --
                if ($hasQuery) {
                    parse_str($urlParams["query"], $params);
                    $id = isset($params["id"]) ? $params["id"] : null;
                } else {
                    $client_json = json_decode(file_get_contents("php://input"));
                    $id = isset($client_json->id) ? $client_json->id : null;
                }

                // If no id is found, we return an error
                // --
                if (!isset($id)) {
                    $error = new Error();
                    $error->setCode(400)
                        ->setError("Requête invalide")
                        ->setMessage("Veuillez envoyer l'id du produit à rechercher. Vous pouvez envoyer l'id par le corps de la requête ou par l'url. L'id par l'url est prioritaire.")
                        ->setLocation("api/v1/lire_un.php")
                        ->sendAndDie();
                }

                // We call the DAO method "findById" to get the product
                // If the request is not successful, we handle the error with the Error class.
                // --
                try {
                    $this->data = $this->produitDao->findById($id) ?? [];
                    $this->message = "Produit trouvé";
                    $this->code = 200;
                } catch (Error $e) {
                    $e->sendAndDie();
                }

                break;

                /**
                 * Autres requêtes
                 * Une erreur 405 et une erreur est retournée.
                 */
            default:
                $error = new Error();
                $error->setCode(405)
                    ->setLocation("api/v1/lire_un.php")
                    ->setError("Methode non autorisée")
                    ->setMessage("Veuillez utiliser la méthode GET pour obtenir un produit. Vous pouvez envoyer l'id par le corps de la requête ou par l'url. L'id par l'url est prioritaire.")
                    ->sendAndDie();
                break;
        }
    }

    public function handleResponse()
    {
        $headers = [
            "Content-Type: application/json",
            "Access-Control-Allow-Origin: *",
            "Access-Control-Allow-Methods: GET",
            "Access-Control-Age: 3600",
            "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
        ];
        $this->response->setCode($this->code)
            ->setData($this->data)
            ->setMessage($this->message)
            ->setHeaders($headers)
            ->sendAndDie();
    }
}
