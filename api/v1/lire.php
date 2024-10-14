<?php

require_once "../../Autoloader.php";

use Model\Dao\ProduitDao as ProduitDao;
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

    public function __construct()
    {
        try {
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
             * GET request : /api/v1/lire.php
             * Seulement les requêtes GET sont autorisées.
             */
            case "GET":
                try {
                    /**
                     * @var Produit[] $data
                     */
                    $products = $this->produitDao->findAll() ?? [];
                    $this->data = array_map(function ($product) {
                        return $product->toArray();
                    }, $products);
                    $this->message = "Produits récupérés avec succès";
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
                $this->error->setCode(405)
                    ->setError("Méthode non autorisée")
                    ->setMessage("Seules les requêtes GET sont autorisées.")
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
