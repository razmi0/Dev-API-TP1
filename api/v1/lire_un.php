<?php

//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                READ ONE PRODUCT ENDPOINT                    |
// |                                                             |
// |          endpoint :  [GET] /api/v1.0/produit/listone        |
// |          file     :        /api/v1/lire_un.php              |
// |          goal     :  retrieve many products from db         |
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
use Controller\Controller;

// Middleware class
use Middleware\Middleware;

// Model classes
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Schema\Schema;

/**
 * 
 * INSTRUCTIONS
 * 
 */
// The request object is configured for GET requests only on this endpoint
$request = new Request([
    "methods" => ["GET"],
]);

// The response object is configured to return a 200 status code and a successfull message
$response = new Response([
    "code" => 200,
    "message" => "Produit trouvé",
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
            $this->checkExpectedData(new Schema(Constant::READ_ONE_SCHEMA));
        }
    )
);

$app->setHandler(

    function () {
        // Get the id from the query
        /**
         * @var string $idInQuery
         */
        $idInQuery = $this->request->getQueryParam("id");

        // Get the id from the body
        /**
         * @var int $idInBody
         */
        $idInBody = $this->request->getDecodedBody("id");

        // If the id is not present in the query or in the body, throw an error
        if (!$idInQuery && !$idInBody) {
            throw Error::HTTP400("Aucun id de produit n'a été fourni dans la requête.", [], "lire_un");
        }

        // Get the id and cast it to an integer if it is from the query
        /**
         * @var int $id
         */
        $id = $idInQuery  ? (int)$idInQuery : $idInBody;

        // Start the DAO
        $dao = new ProductDao();

        // Get the product from the database
        $product = $dao->findById($id);

        // Return the product as an array
        return ["products" => $product->toArray()];
    }
);

// Run the configured controller : 
//          - Run the middlewares sequentially
//          - Run the handler
$app->run();
