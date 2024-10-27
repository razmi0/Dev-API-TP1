<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use Controller\Controller;
use Middleware\Middleware;
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Entities\Product;
use Model\Schema\Schema;

// Create a new Controller object and start it with the request, response, middleware and handler
$app = new Controller(
    // We expect a GET request to this endpoint
    new Request([
        "methods" => ["GET"],
        "endpoint" => "/api/v1/lire.php",
    ]),
    // We expect to return a 200 status code and a message
    new Response([
        "code" => 200,
        "message" => "Produits récupérés avec succès",
    ]),
    // We will use 3 middlewares to check if the request is valid
    new Middleware(
        // Function context : SCOPE : Middleware (class)
        //                    OBJECT : new Middleware ($this)
        function () {
            // Check if the request method is allowed else throw an error ( 405 Method Not Allowed )
            $this->checkAllowedMethods();
            // Check if the request body is a valid JSON else throw an error ( 400 Bad Request )
            $this->checkValidJson();
            // Check if the request body contains the expected data else throw an error ( 400 Bad Request )
            $this->checkExpectedData(new Schema(Constant::READ_ALL));
        }
    ),
    // Business logic at this endpoint : Get all products from the database
    // Function context : SCOPE : Controller (class)
    //                    OBJECT : new Controller ($this)
    function () {

        // Get the limit from the query parameters
        $limitInQuery = $this->request->getQueryParam("limit");

        // Get the limit from the body
        $limitInBody = $this->request->getDecodedBody("limit");

        // Get the limit and cast it to an integer
        $limit = $limitInQuery ? (int)$limitInQuery : (int)$limitInBody;

        // Create a new ProductDao object
        $ProductDao = new ProductDao();

        // Get all products from the database
        /**
         * @var Product[] $allProducts
         */
        $allProducts = $ProductDao->findAll($limit);

        // Map the products to an array
        $productsArray = array_map(fn($product) => $product->toArray(), $allProducts);

        // Return the products array
        return ["products" => $productsArray];
    }

);

// Run the controller
$app->run();
