<?php

require_once "../../Autoloader.php";


use HTTP\Request;
use HTTP\Response;
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

    // Create a new ProduitDao object
    $produitDao = new ProduitDao();

    // Catch the inserted ID from the database
    $allProducts = $produitDao->findAll();

    // Return the inserted ID
    return ["products" => $allProducts];
});
