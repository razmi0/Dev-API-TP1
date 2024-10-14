<?php

require_once "../../Autoloader.php";

use Model\Dao\ProduitDao as ProduitDao;
use Utils\Response as Response;
use Utils\Error as Error;

// Instantiate the controller
// --
$controller = new Controller();

// Handle the request (verify the method and process the request)
// If the method is allowed, the DAO method "delete" is called and the request is processed.
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
             * DELETE request : /api/v1/supprimer.php
             * Seulement les requêtes DELETE sont autorisées.
             */

            case "DELETE":
                $client_json = json_decode(file_get_contents("php://input"));
                $id = isset($client_json->id) ? $client_json->id : null;

                // If no id is found, we return an error
                // --
                if (!isset($id)) {
                    $error = new Error();
                    $error->setCode(400)
                        ->setError("Requête invalide")
                        ->setMessage("Veuillez fournir un id de produit dans le corps de la requête au format JSON.")
                        ->sendAndDie();
                }

                // We call the DAO method "delete" to delete the product
                // If the request is not successful, we handle the error with the Error class.
                // --
                try {
                    $this->data = $this->produitDao->deleteById($id) ?? [];
                    $this->message = "Suppression effectuée avec succès";
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
                    ->setError("Methode non autorisée")
                    ->setMessage("Veuillez utiliser la méthode DELETE pour supprimer un produit.")
                    ->setLocation("api/v1/supprimer.php")
                    ->sendAndDie();
                break;
        }
    }

    public function handleResponse()
    {
        $headers = [
            "Content-Type: application/json",
            "Access-Control-Allow-Origin: *",
            "Access-Control-Allow-Methods: DELETE",
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
