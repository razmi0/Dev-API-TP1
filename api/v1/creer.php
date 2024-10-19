<?php

require_once "../../Autoloader.php";


use DTO\Request;
use DTO\Response;
use Model\Constant;
use Model\Schema\Schema;
use Controller\Controller;
use Model\Dao\ProduitDao;
use Model\Entities\Produit;


/**
 * 
 * The request object is used to parse the client request and extract the necessary
 * information from it. The Controller class will use this object to determine if the
 * request is valid and authorized.
 */
$request = new Request([
    "endpoint" => "/api/v1/creer.php",
    "methods" => ["POST"],
]);


/**
 * 
 * The response object is used to send the valid response expected by the client.
 * The Controller class will use this object to send the response with the appropriate
 * headers and status code.
 * 
 */
$response = new Response([


    "code" => 201,
    "message" => "Produit créé avec succès",


    "headers" => [
        "content_type" => "application/json",
        "origin" => "*",
        "methods" => ["POST"],
        "age" => 3600
    ]
]);


// Get the right schema to agree with the client data
// --
$product_schema = new Schema(Constant::PRODUCT_CREATE_SCHEMA);


// Start the controller with the Request and Response objects
// --

$controller = new Controller($request, $response);

// Handle the request with Request and Response in handleRequest closure and add the product schema
// --

$controller->handleRequest(function () use ($product_schema) {

    // Get the client data
    $client_data = $this->request->getClientDecodedData();

    // Check if the client data is valid
    $is_client_error = $product_schema->safeParse($client_data);

    // If the client data is invalid, throw an error
    if ($is_client_error) {
        throw $this->error
            ->setCode(400)
            ->setError("Données invalides")
            ->setData($product_schema->getErrorResults());
    }

    //Create a new product
    $newProduct = Produit::make($client_data);

    // Create a new product in the database
    $produitDao = new ProduitDao();

    // Catch the inserted ID from the database
    $insertedID = $produitDao->create($newProduct);

    // Return the inserted ID
    return ["id" => intval($insertedID)];
});
