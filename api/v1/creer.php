<?php

require_once "../../Autoloader.php";

use Model\Dao\ProduitDao as ProduitDao;
use Utils\Response;

$headers = [
    "Content-Type: application/json",
    "Access-Control-Allow-Origin: *",
    "Access-Control-Allow-Methods: POST",
    "Access-Control-Age: 3600",
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
];


// Instantiate the controller
// --
$controller = new CreateController();

// Handle the request (verify the method and process the request)
// If the method is allowed, the DAO is instantiate and the request is processed.
// Else, an error message is returned.
// --
$controller->handleRequest();

// Instantiate the response (code, message, data, headers)
// --
$response = new Response($controller->getCode(), $controller->getMessage(), $controller->getData(), $headers);

// Send the response
// --
$response->attachHeaders()->send();

class CreateController
{
    private $produitDao = null;
    private $message = "";
    private $data = [];
    private $code = 0;

    public function __construct()
    {
        $this->produitDao = new ProduitDao();
    }

    public function handleRequest()
    {
        switch ($_SERVER["REQUEST_METHOD"]) {

            /**
             * POST request : /api/v1/creer.php
             * Seulement les requêtes POST sont autorisées.
             */
            case "POST":
                $content = json_decode(file_get_contents("php://input"));
                $this->data = $this->produitDao->create($content);
                $this->message = "Produit créé avec succès";
                $this->code = 201;
                break;

                /**
                 * Autres requêtes
                 * Une erreur 405 et un message est retournée.
                 */
            default:

                $this->message = "Methode non autorisée";
                $this->data = [];
                $this->code = 405;
                break;
        }
    }

   

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }
}
