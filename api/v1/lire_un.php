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
    ["GET"],
    new Request([
        "endpoint" => "/api/v1/lire_un.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produit trouvé",
    ]),
    new Schema(Constant::READ_ONE_SCHEMA)
);

// Handle the request with Request and Response in handleRequest closure and add the product schema
$controller->handleRequest(function () {

    // Get the id from the query
    $idInQuery = $this->request->getQueryParam("id");

    // Get the id from the body
    $idInBody = $this->request->getClientDecodedData("id");

    // If the id is not present in the query or in the body, throw an error
    if (!$idInQuery && !$idInBody) {
        $error_message = "Veuillez envoyer l'id du produit à rechercher. Vous pouvez envoyer l'id par le corps de la requête ou par l'url. L'id par l'url est prioritaire.";
        throw $this->error
            ->setCode(400)
            ->setError("Requête invalide")
            ->setMessage($error_message);
    }

    // Get the id and cast it to an integer
    $id = $idInQuery  ? (int)$idInQuery : (int)$idInBody;

    // Start the DAO
    $produitDao = new ProduitDao();

    // Get the product from the database
    $product = $produitDao->findById($id);

    return ["product" => $product->toArray()];
});
