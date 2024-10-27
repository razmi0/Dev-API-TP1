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
use Controller\Controller;

// Middleware class
use Middleware\Middleware;

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
        [
            "checkAllowedMethods" => [],
            "checkValidJson" => [],
            "checkExpectedData" => new Schema(Constant::UPDATE_SCHEMA),
        ]
    )
);

// We set the handler for the controller to do the business logic
// The handler is the core of the controller, it contains the business logic
$app->setHandler(

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


// Run the configured controller : 
//          - Run the middlewares sequentially
//          - Run the handler
$app->run();
