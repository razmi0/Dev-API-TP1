<?php

//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                 UPDATE PRODUCT ENDPOINT                     |
// |                                                             |
// |          endpoint :  [PUT] /api/v1.0/produit/update         |
// |          file     :        /api/v1/modifier.php             |
// |          goal     :  update a product in db                 |
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
use Model\Schema\Schema;
use Model\Dao\ProductDao;
use Model\Entities\Product;

/**
 * 
 * INSTRUCTIONS
 * 
 */
// The request object is configured for PUT requests only on this endpoint
$request = new Request([
    "methods" => ["PUT"],
]);

// The response object is configured to return a 200 status code and a successfull message
$response = new Response([
    "code" => 200,
    "message" => "Produit modifié avec succès",
]);

// Controller object
$app = new Controller($request, $response);

// Middleware object
$app->setMiddleware(
    new Middleware(

        // Context : Middleware scope and new Middleware object
        // In this context, we access to : Request and Middleware objects

        function () {

            // Check if the request method is allowed else throw an error ( 405 Method Not Allowed )
            $this->checkAllowedMethods();

            // Check if the request body is a valid JSON else throw an error ( 400 Bad Request )
            $this->checkValidJson();

            // Check if the request body contains the expected data else throw an error ( 400 Bad Request )
            $this->checkExpectedData(new Schema(Constant::UPDATE_SCHEMA));
        }
    )
);

// We set the business logic of the controller and run sequentially the middlewares and the handler
$app->run(

    // Context : Controller scope and new Controller object
    // In this scope, we access to : Request, Response, Middleware objects

    function () {
        // Get the client data
        /**
         * @var array Partial Product data
         */
        $client_data = $this->request->getDecodedBody();

        // Create a new product
        $product = Product::make($client_data);

        // Start the DAO
        $dao = new ProductDao();

        // Update the product in the database and store the id
        $id = $dao->update($product);

        // Cast the id to an integer and return it to the controller
        return ["id" => (int)$id];
    }
);
