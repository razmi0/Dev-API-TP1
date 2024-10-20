<?php

require_once "../../Autoloader.php";


use DTO\Request;
use DTO\Response;
use Controller\Controller;
use Model\Dao\ProduitDao;

// Start the controller with the Request and Response objects
$controller = new Controller(
    ["GET"],
    new Request([
        "endpoint" => "/api/v1/lire.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produits récupérés avec succès",
    ])
);

// Handle the request with Request and Response in handleRequest closure and add the product schema
$controller->handleRequest(function () {

    // No data to parse, just return the products
    // --

    // Create a new ProduitDao object
    $produitDao = new ProduitDao();

    // Catch the inserted ID from the database
    $allProducts = $produitDao->findAll();

    // Return the inserted ID
    return $allProducts;
});
