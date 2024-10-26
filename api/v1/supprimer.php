<?php

require_once "../../Autoloader.php";


use HTTP\Request;
use HTTP\Response;
use Controller\Controller;
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Schema\Schema;
use HTTP\Error;
use Middleware\Middleware;

// Start the controller with the Request and Response objects
$app = $controller = new Controller(
    new Request([
        "methods" => ["DELETE"],
        "endpoint" => "/api/v1/supprimer.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produit supprimé avec succès",
    ]),
    new Middleware([
        "checkAllowedMethods" => [],
        "checkValidJson" => [],
        "checkExpectedData" => new Schema(Constant::DELETE_SCHEMA),
    ]),
    function () {
        // Get the id from the body
        $id = $this->request->getDecodedBody("id");

        // If the id is not present in the body, throw an error
        if (!$id) {
            $error_message = "Veuillez fournir un id de produit dans le corps de la requête au format JSON.";
            throw Error::HTTP400("Données invalides : " .  $error_message, []);
        }

        // Start the DAO
        $ProductDao = new ProductDao();

        // Get the product from the database
        $affectedRows = $ProductDao->deleteById($id);

        // If no product was found, we send a 204 with no content in response body as HTTP specification states
        if ($affectedRows === 0) {
            throw Error::HTTP204("Aucun produit trouvé", ["id" => $id]);
        }

        // Return the id
        return ["id" => $id];
    }
);

$app->run();
