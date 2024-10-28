<?php

//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |                  READ PRODUCT ENDPOINT                      |
// |                                                             |
// |          endpoint :  [GET] /api/v1.0/produit/list           |
// |          file     :        /api/v1/lire.php                 |
// |          goal     :  retrieve all products from db          |
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

// The request object is configured for GET requests only on this endpoint
$request = new Request([
    "methods" => ["GET"],
]);

// The response object is configured to return a 200 status code and a successfull message
$response = new Response([
    "code" => 200,
    "message" => "Produits récupérés avec succès",
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
            $this->checkExpectedData(new Schema(Constant::READ_ALL));
        }
    )
);

// We set the business logic of the controller and run sequentially the middlewares and the handler
$app->run(

    // Context : Controller scope and new Controller object
    // In this scope, we access to : Request, Response, Middleware objects

    function () {
        // Get the limit from the query parameters
        /**
         * @var string $limitInQuery
         */
        $limitInQuery = $this->request->getQueryParam("limit");

        // Get the limit from the body
        /**
         * @var integer $limitInBody
         */
        $limitInBody = $this->request->getDecodedBody("limit");

        // Get the limit and cast it to an integer if it is from the query
        /**
         * @var int $limit
         */
        $limit = $limitInQuery ? (int)$limitInQuery : $limitInBody;

        // Create a new ProductDao object
        $dao = new ProductDao();

        // Get all products from the database (with a limit if provided)
        $allProducts = $dao->findAll($limit);

        // Map the products to an array
        $productsArray = array_map(fn($product) => $product->toArray(), $allProducts);

        // Return the products array
        return ["products" => $productsArray];
    }
);
