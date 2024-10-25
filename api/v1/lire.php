<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use Controller\Controller;
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Entities\Product;
use Model\Schema\Schema;

// Start the controller with the Request and Response objects
new Controller(
    new Request([
        "methods" => ["GET"],
        "endpoint" => "/api/v1/lire.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produits récupérés avec succès",
    ]),
    // We validate "limit" as an optional integer extending from 1 to infinity (null)
    new Schema(Constant::READ_ALL),
    function () {

        $limitInQuery = $this->request->getQueryParam("limit");
        $limitInBody = $this->request->getDecodedBody("limit");

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

        // Return the inserted ID
        return ["products" => $productsArray];
    }

);
