<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use Model\Dao\Connection as Connection;
use Model\Services\ProduitService as ProduitService;
use Utils\Response as Response;
use PDO;
use PDOException;


class ProduitDao
{
    private $table_name  = "T_PRODUIT";


    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * @param Produit $content
     */
    public function create($content)
    {
        // Connection step ( open connection to the database via PDO instantiation and set PDO attributes )
        // --
        $connection = new Connection();
        $pdo = $connection->getPDO();

        // Service step ( validate the data and create the product object )
        // --
        $produitService = new ProduitService();
        $produit = $produitService->createProduit($content);

        // Database Access step ( build the query, prepare it, execute it and return the result )
        // --
        $query = "INSERT INTO T_PRODUIT (product_name, description, prix, date_creation) VALUES (:name, :description, :prix, :date)";

        try {

            // Set PDO attributes
            // --
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // Verify the preparation of the query
            // --
            $prepared = $pdo->prepare($query);
            if ($prepared === false) {
                throw new PDOException("Erreur lors de la préparation de la requête");
            }

            // Bind the parameters
            // --
            $prepared->bindParam(':name', $produit->getProductName(), PDO::PARAM_STR);
            $prepared->bindParam(':description', $produit->getDescription(), PDO::PARAM_STR);
            $prepared->bindParam(':prix', $produit->getPrix(), PDO::PARAM_STR);
            $prepared->bindParam(':date', $produit->getDateCreation(), PDO::PARAM_STR);


            // Verify the execution of the query
            // --
            $executionResult = $prepared->execute();
            if ($executionResult) {
                $result = $prepared->fetchAll();
            } else {
                throw new PDOException("Erreur lors de l'exécution de la requête");
            }
        } catch (PDOException $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            // --
            $response = new Response(500, "Erreur lors de l'envoi de la requête", [], []);
            error_log($e->getMessage());
            $response->send();
        }
        return $result;
    }


    /**
     * @return Produit[]
     */
    static function findAll()
    {
        // Connection step ( open connection to the database via PDO instantiation and set PDO attributes )
        // --
        $connection = new Connection();
        $pdo = $connection->getPDO();

        // Build the query
        // --
        $query = "SELECT * FROM T_PRODUIT ORDER BY date_creation DESC";

        try {
            // Set PDO attributes
            // --
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $prepared = $pdo->prepare($query);

            // Verify the preparation of the query
            // --
            if ($prepared === false) {
                throw new PDOException("Erreur lors de la préparation de la requête");
            }

            // Verify the execution of the query
            // --
            $executionResult = $prepared->execute();
            if ($executionResult) {
                $result = $prepared->fetchAll();
            } else {
                throw new PDOException("Erreur lors de l'exécution de la requête");
            }
        } catch (PDOException $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            // --
            $response = new Response(500, "Erreur lors de l'envoi de la requête", [], []);
            error_log($e->getMessage());
            $response->send();
        }

        return $result;
    }



    public function findById($id) {}

    public function delete($id) {}

    public function update($id) {}
}
