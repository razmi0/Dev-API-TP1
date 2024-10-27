<?php

require_once "../../Autoloader.php";

use HTTP\Request;
use HTTP\Response;
use HTTP\Error;
use Controller\Controller;
use Middleware\Middleware;
use Model\Constant;
use Model\Dao\ProductDao;
use Model\Schema\Schema;

$app = new Controller(
    new Request([
        "methods" => ["GET"],
        "endpoint" => "/api/v1/lire_un.php",
    ]),
    new Response([
        "code" => 200,
        "message" => "Produit trouvé",
    ]),
    new Middleware(
        function () {
            $this->checkAllowedMethods();
            $this->checkValidJson();
            $this->checkExpectedData(new Schema(Constant::READ_ONE_SCHEMA));
        }
    ),
    function () {
        // Get the id from the query ( string | null )
        $idInQuery = $this->request->getQueryParam("id");

        // Get the id from the body ( integer | null )
        $idInBody = $this->request->getDecodedBody("id");

        // If the id is not present in the query or in the body, throw an error
        if (!$idInQuery && !$idInBody) {
            throw Error::HTTP400("Aucun id de produit n'a été fourni dans la requête.", [], "lire_un");
        }

        // Get the id and cast it to an integer if it is from the query
        /**
         * @var int $id
         */
        $id = $idInQuery  ? (int)$idInQuery : $idInBody;

        // Start the DAO
        $dao = new ProductDao();

        // Get the product from the database
        $product = $dao->findById($id);

        // Return the product as an array
        return ["products" => $product->toArray()];
    }
);

$app->run();
