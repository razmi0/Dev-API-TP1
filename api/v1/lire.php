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
    new Request([
        "methods" => ["GET"],
        "endpoint" => "/api/v1/lire.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produits récupérés avec succès",
    ]),
    new Middleware([
        "checkAllowedMethods" => [],
        "checkValidJson" => [],
        // We validate "limit" as an optional integer extending from 1 to infinity (null)
        "checkExpectedData" => new Schema(Constant::READ_ALL),
    ]),
    function () {

        // Get the limit from the query parameters
        $limitInQuery = $this->request->getQueryParam("limit");

        // Get the limit from the body
        $limitInBody = $this->request->getDecodedBody("limit");

        // Get the limit
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
