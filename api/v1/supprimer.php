<?php

require_once "../../Autoloader.php";


use HTTP\Request;
use HTTP\Response;
use Controller\Controller;
use Model\Constant;
use Model\Dao\ProduitDao;
use Model\Schema\Schema;

// Start the controller with the Request and Response objects
$controller = new Controller(
    ["DELETE"],
    new Request([
        "endpoint" => "/api/v1/supprimer.php",
    ]),
    new Response([
        "code" => 204,
        "message" => "Produit supprimé avec succès",
    ]),
    new Schema(Constant::ID_SCHEMA)
);

// Handle the request with Request and Response in handleRequest closure and add the product schema
$controller->handleRequest(function () {

    // Get the id from the body
    $id = $this->request->getClientDecodedData("id");

    // If the id is not present in the query or in the body, throw an error
    if (!$id) {
        $error_message = "Veuillez fournir un id de produit dans le corps de la requête au format JSON.";
        throw $this->error
            ->setCode(400)
            ->setError("Requête invalide")
            ->setMessage($error_message);
    }

    // Start the DAO
    $produitDao = new ProduitDao();

    // Get the product from the database
    $product = $produitDao->deleteById($id);

    return ["product" => $product];
});
