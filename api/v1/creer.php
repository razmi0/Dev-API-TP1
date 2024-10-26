<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use Model\Constant;
use Model\Schema\Schema;
use Controller\Controller;
use Middleware\Middleware;
use Model\Dao\ProductDao;
use Model\Entities\Product;

$app = new Controller(
    new Request([
        "methods" => ["POST"],
        "endpoint" => "/api/v1/creer.php",
    ]),
    new Response([
        "code" => 201,
        "message" => "Produit créé avec succès",
    ]),
    new Middleware([
        "checkAllowedMethods" => [],
        "checkValidJson" => [],
        "checkExpectedData" => new Schema(Constant::CREATE_SCHEMA),
    ]),
    function () {
        // Get the decoded client data
        $client_data = $this->request->getDecodedBody();

        //Create a new product
        $newProduct = Product::make($client_data);

        // Connect to the database
        $ProductDao = new ProductDao();

        // Create product in db and catch the inserted ID from the database
        $insertedID = $ProductDao->create($newProduct);

        // Cast the id to integer and return the inserted ID to controller
        return ["id" => intval($insertedID)];
    },
);


$app->run();
