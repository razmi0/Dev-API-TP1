<?php

require_once "../../Autoloader.php";


use HTTP\Request;
use HTTP\Response;
use Model\Constant;
use Model\Schema\Schema;
use Controller\Controller;
use Model\Dao\ProductDao;
use Model\Entities\Produit;
use HTTP\Error;

// Start the controller with the Request and Response objects
$controller = new Controller(

    new Request([
        "methods" => ["PUT"],
        "endpoint" => "/api/v1/modifier.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produit modifié avec succès",
    ]),
    new Schema(Constant::UPDATE_SCHEMA),
    function () {


        // Get the client data
        $client_data = $this->request->getDecodedBody();

        $product = Produit::make($client_data);

        $productDao = new ProductDao();

        $id = $productDao->update($product);

        return ["id" => $id];
    }
);
