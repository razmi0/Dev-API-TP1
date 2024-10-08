<?php

require_once "../../Autoloader.php";
use Model\Dao\ProduitDao as ProduitDao;



Controller::setHeaders();
Controller::handleRequest();


/**
 * @method setHeaders() Sets the headers for the response.
 * @method handleRequest() Handles the request.
 */
class Controller {

    /**
     * Sets the headers for the response.
     */
    public static function setHeaders() {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public static function handleRequest() {
        switch ($_SERVER["REQUEST_METHOD"]) {
            
            /**
             * GET request : /api/v1/lire.php
             * Seulement les requêtes GET sont autorisées.
             */
            case "GET":
                $response = ProduitDao::findAll();
                http_response_code(200);
                echo json_encode($response);
                break;

            /**
             * Autres requêtes
             * Une erreur 405 et un message est retournée.
             */
            default:
                $response = ["message" => "Methode non autorisée"];
                http_response_code(405);
                echo json_encode($response);
                break;
        }
    }

    
}



