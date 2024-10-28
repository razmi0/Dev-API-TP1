<?php


//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                 CREATE PRODUCT ENDPOINT                     |
// |                                                             |
// |          endpoint :  [POST] /api/v1.0/produit/new           |
// |          file     :         /api/v1/creer.php               |
// |          goal     :  create a product in database           |
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

// Controller class
use Controller\ProductController as Controller;

// Middleware class
use Middleware\ProductMiddleware as Middleware;

// Model classes
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Entities\Product;
use Model\Schema\Schema;

/**
 * 
 * INSTRUCTIONS
 * 
 */

// The request object is configured for POST requests only on this endpoint
$request = new Request([
    "methods" => ["POST"],
]);

// The response object is configured to return a 201 status code and a successfull message
$response = new Response([
    "code" => 201,
    "message" => "Produit créé avec succès",
]);

// Controller object
$app = new Controller($request, $response);

// Middleware object
$app->setMiddleware(
    new Middleware(

        // Context : Middleware scope and new Middleware object
        // In this context, we access to : Request and Middleware objects

        function () {
            // Check if the request method is allowed else throw an error               ( 405 Method Not Allowed )
            $this->checkAllowedMethods();

            // Check if the request body is a valid JSON else throw an error            ( 400 Bad Request )
            $this->checkValidJson();

            // Check if the request body contains the expected data else throw an error ( 400 Bad Request )
            $this->checkExpectedData(new Schema(Constant::CREATE_SCHEMA));
        }
    )
);

// We set the business logic of the controller and run sequentially the middlewares and the handler
$app->run(

    // Context : Controller scope and new Controller object
    // In this scope, we access to : Request, Response, Middleware objects

    function () {
        /**
         * Decoded body from the request
         * @var array $client_data
         */
        $client_data = $this->request->getDecodedBody();

        // Create a new product
        $newProduct = Product::make($client_data);

        // Start the DAO
        $dao = new ProductDao();

        // The DAO create method create a new product in the database and return the inserted ID
        /**
         *  @var string $insertedID
         */
        $insertedID = $dao->create($newProduct);

        // Cast the id to integer and return the inserted ID to controller
        return ["id" => (int)$insertedID];
    },
);
