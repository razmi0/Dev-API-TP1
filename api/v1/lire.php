<?php

require_once "../../Autoloader.php";
use Model\Dao\ProduitDao as ProduitDao;



Controller::setHeaders();
Controller::handleRequest();


/**
 * @method setHeaders() Sets the headers for the response.
 * @method handleRequest() Handles the request.
 * @method sendResponse($response, $code) Sends the response to the client.
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
                self::sendResponse($response, 200);
                break;

            /**
             * Autres requêtes
             * Une erreur 405 et un message est retournée.
             */
            default:
                $response = ["message" => "Methode non autorisée"];
                self::sendResponse($response, 405);
                break;
        }
    }

    /**
     * Sends the response to the client.
     * @param $response The response to send.
     * @param $code The HTTP status code to send.
     */
    private static function sendResponse($response, $code) {
        http_response_code($code);
        echo json_encode($response);
    }

    
}



