<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use Controller\Controller;
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Schema\Schema;
use Utils\Console;

new Controller(
    new Request([
        "methods" => ["GET"],
        "endpoint" => "/api/v1/lire_des.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produits trouvés",
    ]),
    new Schema(Constant::READ_MANY_SCHEMA),
    function () {
        // Get the ids from the query as an associative array
        $idsInQuery = $this->request->getQueryParam("id");

        // Get the ids from the body
        $idsInBody = $this->request->getDecodedBody("id");

        // If the id is not present in the query or in the body, throw an error
        if (!$idsInQuery && !$idsInBody) {
            throw $this->error->HTTP400("Aucun ids de produits n'a été fourni dans la requête.");
        }

        // Get the ids and cast them to an array of integers
        $ids = $idsInQuery
            ? array_map(fn($id) => (int)$id, $idsInQuery)
            : array_map(fn($id) => (int)$id, $idsInBody);

        // Start the DAO
        $dao = new ProductDao();

        // Get the product from the database
        $products = $dao->findManyById($ids);

        return ["products" => array_map(fn($product) => $product->toArray(), $products)];
    }
);
