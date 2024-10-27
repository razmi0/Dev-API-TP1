<?php

//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |               READ MANY PRODUCT ENDPOINT                    |
// |                                                             |
// |          endpoint :  [GET] /api/v1.0/produit/listmany       |
// |          file     :        /api/v1/lire_des.php             |
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
    "endpoint" => "/api/v1/lire_des.php",
]);

// The response object is configured to return a 200 status code and a successfull message
$response = new Response([
    "code" => 200,
    "message" => "Produits trouvés",
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
            $this->checkExpectedData(new Schema(Constant::READ_MANY_SCHEMA));
        }
    )
);

// We set the business logic of the controller and run sequentially the middlewares and the handler
$app->run(

    // Context : Controller scope and new Controller object
    // In this scope, we access to : Request, Response, Middleware objects

    function () {

        // Get the ids from the query as an associative array
        /**
         * @var string[] $idsInQuery
         */
        $idsInQuery = $this->request->getQueryParam("id");

        // Get the ids from the body
        /**
         * @var int[] $idsInBody
         */
        $idsInBody = $this->request->getDecodedBody("id");

        // If the ids are not present in the query or in the body, throw an error
        if (!$idsInQuery && !$idsInBody) {
            throw Error::HTTP400("Aucun ids de produits n'a été fourni dans la requête.");
        }

        // Get the ids and cast them to an array of integers if they are from the query
        /**
         * @var int[] $ids
         */
        $ids = $idsInQuery
            ? array_map(fn($id) => (int)$id, $idsInQuery)
            : $idsInBody;

        // Start the DAO
        $dao = new ProductDao();

        // Get the product from the database
        $products = $dao->findManyById($ids);

        // Map the products to an array
        $productArray = array_map(fn($product) => $product->toArray(), $products);

        // Return the products array to the controller
        return ["products" => $productArray];
    }
);
