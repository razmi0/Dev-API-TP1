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


// Instantiate the controller
// --
$controller = new Controller($request, $response);
$controller->handleRequest(function () {

    /**
     * A schema is a set of rules that the client data must follow.
     * Provided with a valid template, the Schema class can parse
     * the client data and validate it against the rules explicitly
     * defined in the schema by the consumer.
     */
    $schema = new Schema([

        "name" => [
            "type" => Constant::NAME_TYPE, // string
            "range" => Constant::NAME_LENGTH, // [1, 65]
            "regex" => Constant::NAME_REGEX // "/^[a-zA-Z0-9 ]+$/"
        ],


        "description" => [
            "type" => Constant::DESCRIPTION_TYPE, // string
            "range" => Constant::DESCRIPTION_LENGTH, // [1, 65000]
            "regex" => Constant::DESCRIPTION_REGEX // "/^[a-zA-Z0-9 ]+$/"
        ],


        "prix" => [
            "type" => Constant::PRICE_TYPE, // double
            "range" => Constant::PRICE_RANGE, // [0, null]
            "regex" => Constant::PRICE_REGEX // "/^[0-9.]+$/"
        ]
    ]);

    $tested_schema = $schema->safeParse($this->client_decoded_data);

    if ($tested_schema->getHasError()) {
        $errorResults = $tested_schema->getErrorResults();
        throw $this->error
            ->setCode(400)
            ->setError("Données invalides")
            ->setData($errorResults);
    }

    $newProduct = new Produit();
    $newProduct
        ->setName($this->client_decoded_data["name"])
        ->setDescription($this->client_decoded_data["description"])
        ->setPrix($this->client_decoded_data["prix"])
        ->setDateCreation(date("Y-m-d H:i:s"));

    $produitDao = new ProduitDao();
    $insertedID = $produitDao->create($newProduct);

    return ["id" => intval($insertedID)];
});
