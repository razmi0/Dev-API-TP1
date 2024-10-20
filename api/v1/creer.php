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
$controller = new Controller(
    [
        "POST"
    ],
    new Request([
        "endpoint" => "/api/v1/creer.php",
    ]),
    new Response([
        "code" => 201,
        "message" => "Produit créé avec succès",
    ]),
    new Schema(Constant::PRODUCT_CREATE_SCHEMA)
);

// Handle the request with Request and Response in handleRequest closure and add the product schema
$controller->handleRequest(function () {

    // Get the client data
    $client_data = $this->request->getClientDecodedData();

    // If the client data is invalid, throw an error
    if ($this->data_parsed->getHasError()) {
        throw $this->error
            ->setCode(400)
            ->setError("Données invalides")
            ->setData($this->data_parsed->getErrorResults());
    }

    //Create a new product
    $newProduct = Produit::make($client_data);

    // Connect to the database
    $produitDao = new ProduitDao();

    // Catch the inserted ID from the database
    $insertedID = $produitDao->create($newProduct);

    // Return the inserted ID
    return ["id" => intval($insertedID)];
});
