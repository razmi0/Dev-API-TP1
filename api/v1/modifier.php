<?php

require_once "../../Autoloader.php";

use Model\Dao\ProduitDao as ProduitDao;
use Utils\Validator as Validator;
use Utils\Response as Response;
use Utils\Error as Error;



$controller = new Controller();

// We handle the request calling the DAO.
// If the request is not successful, we handle the error with the Error class.
// --
$controller->handleRequest();

// Else if the request is successful, we handle the response with the Response class.
// --
$controller->handleResponse();


class Controller
{
    private $produitDao = null;
    private $response = null;
    private $message = "";
    private $data = [];
    private $code = 0;
    private $error = null;
    private $validator = null;


    public function __construct()
    {
        try {
            $this->validator = new Validator();
            $this->produitDao = new ProduitDao();
            $this->response = new Response();
            $this->error = new Error();
            $this->error->setLocation("api/v1/lire.php");
        } catch (Error $e) {
            $e->sendAndDie();
        }
    }

    public function handleRequest()
    {
        switch ($_SERVER["REQUEST_METHOD"]) {

                /**
             * PUT request : /api/v1/modifier.php
             * Seulement les requêtes PUT sont autorisées.
             */
            case "PUT":
                try {

                    $client_json = json_decode(file_get_contents("php://input"));
                    $produit = $this->validator->createProduit($client_json);
                    $this->data = $this->produitDao->update($produit) ?? [];
                    $this->message = "Produit modifié avec succès";
                    $this->code = 200;
                } catch (Error $e) {
                    $e->sendAndDie();
                }
                break;


                /**
                 * Autres requêtes
                 * Une erreur 405 et un message est retournée.
                 */
            default:
                $this->error
                    ->setCode(405)
                    ->setError("Méthode non autorisée")
                    ->setLocation("api/v1/modifier.php")
                    ->setMessage("Seules les requêtes PUT sont autorisées.")
                    ->sendAndDie();
                break;
        }
    }

    public function handleResponse()
    {
        $headers = [
            "Content-Type: application/json",
            "Access-Control-Allow-Origin: *",
            "Access-Control-Allow-Methods: PUT",
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
