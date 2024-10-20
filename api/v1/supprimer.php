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
        "code" => 200,
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
    $affectedRows = $produitDao->deleteById($id);

    // If no product was found, we send a 204 with no content in response body as HTTP specification states
    if ($affectedRows === 0) {
        $this->error
            ->setError("Aucun produit trouvé")
            ->setCode(204)
            ->setMessage("L'utilisateur a tenté de supprimer un produit qui n'existe pas")
            ->setData(["id" => $id]);
        throw $this->error;
    }

    return ["product" => $affectedRows];
});
