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

// Start the controller with the Request and Response objects
$app = new Controller(

    new Request([
        "methods" => ["PUT"],
        "endpoint" => "/api/v1/modifier.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produit modifié avec succès",
    ]),
    new Middleware([
        "checkAllowedMethods" => [],
        "checkValidJson" => [],
        "checkExpectedData" => new Schema(Constant::UPDATE_SCHEMA),
    ]),
    function () {
        // Get the client data
        $client_data = $this->request->getDecodedBody();

        // Create a new product
        $product = Product::make($client_data);

        // Start the DAO
        $productDao = new ProductDao();

        // Update the product in the database and catch the id
        $id = $productDao->update($product);

        // Return the id
        return ["id" => $id];
    }
);

$app->run();
