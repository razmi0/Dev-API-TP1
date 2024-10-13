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
             * POST request : /api/v1/creer.php
             * Seulement les requêtes POST sont autorisées.
             */
            case "POST":
                try {
                    $content = json_decode(file_get_contents("php://input"));
                    $this->data = $this->produitDao->create($content) ?? [];
                    $this->message = "Produit créé avec succès";
                    $this->code = 201;
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
                    ->setMessage("Veuillez utiliser la méthode POST pour créer un produit")
                    ->setLocation("api/v1/creer.php")
                    ->sendAndDie();
                break;
        }
    }

    public function handleResponse()
    {
        $headers = [
            "Content-Type: application/json",
            "Access-Control-Allow-Origin: *",
            "Access-Control-Allow-Methods: POST",
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
