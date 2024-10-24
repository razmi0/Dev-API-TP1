<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use Controller\Controller;
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Schema\Schema;

new Controller(
    new Request([
        "methods" => ["GET"],
        "endpoint" => "/api/v1/lire_un.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produit trouvé",
    ]),
    new Schema(Constant::READ_ONE_SCHEMA),
    function () {

        // Get the id from the query
        $idInQuery = $this->request->getQueryParam("id");

        // Get the id from the body
        $idInBody = $this->request->getDecodedBody("id");

        // If the id is not present in the query or in the body, throw an error
        if (!$idInQuery && !$idInBody) {
            $error_message =
                "Veuillez envoyer l'id du produit à rechercher. Vous pouvez envoyer l'id par le corps de la requête ou par l'url. L'id par l'url est prioritaire.";
            throw $this->error
                ->setCode(400)
                ->setError("Requête invalide")
                ->setMessage($error_message);
        }

        // Get the id and cast it to an integer
        $id = $idInQuery  ? (int)$idInQuery : (int)$idInBody;

        // Start the DAO
        $dao = new ProductDao();

        // Get the product from the database
        $product = $dao->findById($id);

        return ["products" => $product->toArray()];
    }
);
