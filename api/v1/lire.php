<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use Controller\Controller;
use Model\Dao\ProductDao;
use Model\Entities\Product;

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
    // No client data expected on this endpoint so no schema validation step is needed
    null,
    function () {
        // Set the error location for debugging purpose
        $this->error->setLocation("/api/v1/lire.php");

        // Create a new ProductDao object
        $ProductDao = new ProductDao();

        // Get all products from the database
        /**
         * @var Produit[] $allProducts
         */
        $allProducts = $ProductDao->findAll();

        // Map the products to an array
        $productsArray = array_map(fn($product) => $product->toArray(), $allProducts);

        // Return the inserted ID
        return ["products" => $productsArray];
    }

);
