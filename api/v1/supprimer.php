<?php

//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                DELETE PRODUCT ENDPOINT                      |
// |                                                             |
// |          endpoint :  [DELETE] /api/v1.0/produit/delete      |
// |          file     :           /api/v1/supprimer.php         |
// |          goal     :  delete one product from db             |
// |_____________________________________________________________|
//

require_once "../../Autoloader.php";


/**
 * 
 * IMPORTS
 * 
 */
// HTTP classes
use HTTP\Request;
use HTTP\Response;
use HTTP\Error;

// Controller class
use Controller\ProductController as Controller;

// Middleware class
use Middleware\ProductMiddleware as Middleware;

// Model classes
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Schema\Schema;

/**
 * 
 * INSTRUCTIONS
 * 
 */

// The request object is configured for DELETE requests only on this endpoint
$request = new Request([
    "methods" => ["DELETE"],
]);

// The response object is configured to return a 200 status code and a successfull message
$response = new Response([
    "code" => 200,
    "message" => "Produit supprimé avec succès",
]);

// Controller object
$app = new Controller($request, $response);

// Middleware object
$app->setMiddleware(
    new Middleware(

        // Context : Middleware scope and new Middleware object
        // In this context, we access to : Request and Middleware objects

        function () {
            // Check if the request method is allowed else throw an error                ( 405 Method Not Allowed )
            $this->checkAllowedMethods();

            // Check if the request body is a valid JSON else throw an error             ( 400 Bad Request )
            $this->checkValidJson();

            // Check if the request body contains the expected data else throw an error  ( 400 Bad Request )
            $this->checkExpectedData(new Schema(Constant::DELETE_SCHEMA));
        }
    )
);

// We set the business logic of the controller and run sequentially the middlewares and the handler
$app->run(

    function () {
        // Get the id from the body
        /**
         * @var int $id
         */
        $id = $this->request->getDecodedBody("id");

        // If the id is not present in the body, throw an error
        if (!$id) {
            $error_message = "Veuillez fournir un id de produit dans le corps de la requête au format JSON.";
            throw Error::HTTP400("Données invalides : " .  $error_message, []);
        }

        // Start the DAO
        $dao = new ProductDao();

        // Get the product from the database
        $affectedRows = $dao->deleteById($id);

        // If no product was found, we send a 204 with no content in response body as HTTP specification states
        if ($affectedRows === 0) {
            throw Error::HTTP204("Aucun produit trouvé", ["id" => $id]);
        }

        // Return the id
        return ["id" => $id];
    }
);
