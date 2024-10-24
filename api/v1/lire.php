<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use Model\Constant;
use Model\Schema\Schema;
use Controller\Controller;
use Model\Dao\ProduitDao;
use Model\Entities\Produit;

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

        // Create a new ProduitDao object
        $produitDao = new ProduitDao();

        // Get all products from the database
        /**
         * @var Produit[] $allProducts
         */
        $allProducts = $produitDao->findAll();

        // Map the products to an array
        $productsArray = array_map(fn($product) => $product->toArray(), $allProducts);

        // Return the inserted ID
        return ["products" => $productsArray];
    }

);
