<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use Model\Dao\Connection as Connection;
use HTTP\Error as Error;
use PDO;



class ProductDao
{
    private ?PDO $pdo = null;
    private ?Connection $connection = null;
    private ?Error $error = null;

    public function __construct()
    {
        try {

            $this->connection = new Connection();
            $this->pdo = $this->connection->getPDO();
            $this->error = new Error();
        } catch (Error $e) {
            throw $e;
        }
    }



    private function bindParamsCreate($produit, $prepared)
    {
        try {
            $name = $produit->getProductName();
            $description = $produit->getDescription();
            $prix = $produit->getPrix();
            $date = $produit->getDateCreation();

            $prepared->bindParam(':name', $name, PDO::PARAM_STR);
            $prepared->bindParam(':description', $description, PDO::PARAM_STR);
            // we use PARAM_STR for the type double :prix because PARAM_INT will lead to a loss of precision or error
            $prepared->bindParam(':prix', $prix, PDO::PARAM_INT);
            $prepared->bindParam(':date', $date, PDO::PARAM_STR);
        } catch (Error $e) {
            $this->error->setLocation("model/dao/ProductDao.php-> bindParamsCreate");
            throw $e;
        }


        return $prepared;
    }

    /**
     * @param Produit $produit
     * @throws Error 
     * @return string $insertedId
     */
    public function create($produit)
    {
        try {
            $this->error->setLocation("model/dao/ProductDao.php-> create");
            $query = "INSERT INTO T_PRODUIT (name, description, prix, date_creation) VALUES (:name, :description, :prix, :date)";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Bind the parameters
            $prepared = $this->bindParamsCreate($produit, $prepared);

            // Verify the execution of the query
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error;
            }

            // If all went good, we will return the id of the last inserted product in db to the controller
            return $this->pdo->lastInsertId();
        } catch (Error $e) {
            // If an error was catch, we send an informative error message back to the controller
            throw $e;
        }
    }


    /**
     * 
     * @description Find all products
     * @throws Error
     * @return Produit[]
     * 
     */
    public function findAll()
    {

        try {
            $this->error->setLocation("model/dao/ProductDao.php-> findAll");

            // Build the query
            $query = "SELECT * FROM T_PRODUIT ORDER BY date_creation DESC";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Verify the execution of the query
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error;
            }
            $products_from_db = $prepared->fetchAll();

            // If no product was found, we send a response with a 404 status code and an error message
            if (count($products_from_db) == 0) {
                throw $this->error->HTTP404("La base de données est vide");
            }

            // We map the products from the database to a new array of products entities and return it to the controller
            return array_map(fn($product) => Produit::make($product), $products_from_db);
        } catch (Error $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            throw $e;
        }
    }


    /**
     * 
     * @param $id
     * @throws Error
     * @return Produit
     * 
     */
    public function findById(int $id): Produit
    {


        try {
            $this->error->setLocation("model/dao/ProductDao.php-> findById");

            // Build the query
            $query = "SELECT * FROM T_PRODUIT WHERE id = :id";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Bind the parameters
            $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            // Verify the execution of the query
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error;
            }

            // Fetch the result
            $result = $prepared->fetch();

            // If no product was found, we send a response with a 404 status code and an error message
            if (!$result) {
                throw $this->error->HTTP404("Aucun produit trouvé", ["id" => $id], "model/dao/ProductDao.php-> findById");
            }

            // Create a new product object and return it to the controller
            return Produit::make($result);
        } catch (Error $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            throw $e;
        }
    }

    /**
     * 
     * @description Delete a product by its id
     * @param $id
     * @throws Error
     * @return int
     * 
     */
    public function deleteById(int $id): int
    {

        try {
            // Build the query
            $query = "DELETE FROM T_PRODUIT WHERE id = :id";
            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Bind the parameters
            $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            // Verify the execution of the query
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error->HTTP500("Erreur lors de la suppression du produit", [], "model/dao/ProductDao.php -> deleteById");
            }

            // Affected rows in the database
            $affectedRows = $prepared->rowCount();

            // return the number of affected rows
            return $affectedRows;
        } catch (Error $e) {
            throw $e;
        }
    }

    /**
     * 
     * Helper method to bind the parameters for the update method
     * @param Produit $produit
     * @param PDOStatement $prepared
     * @return PDOStatement
     * 
     */
    private function bindParamsUpdate($produit, $prepared)
    {
        try {
            $id = $produit->getId();
            $name = $produit->getProductName();
            $description = $produit->getDescription();
            $prix = $produit->getPrix();

            $prepared->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared->bindParam(':name', $name);
            $prepared->bindParam(':description', $description);
            $prepared->bindValue(':prix', $prix);
        } catch (Error $e) {
            $this->error->setLocation("model/dao/ProductDao.php-> bindParamsUpdate");
            throw $e;
        }

        return $prepared;
    }

    /**
     * 
     * @param Produit $produit
     * @throws Error
     * @return array
     * 
     */
    public function update(Produit $produit): int
    {

        try {

            $product_id = $produit->getId();

            $error = ["Erreur lors de la mise à jour du produit", ["id" => $product_id], "model/dao/ProductDao.php -> update"];
            // Database Access step ( build the query, prepare it, execute it and return the result )
            $query =  $this->buildQuery($produit);

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error->HTTP500(...$error);
            }

            // Bind the parameters
            $prepared = $this->bindParamsUpdate($produit, $prepared);

            // Verify the execution of the query
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error->HTTP500(...$error);
            }
            $affectedRows = $prepared->rowCount();

            if ($affectedRows == 0) {
                throw $this->error->HTTP204("Aucune modification", ["id" => $product_id]);
            }

            // If all went good, we will return the id of the last inserted product to the controller
            return $product_id;
        } catch (Error $e) {
            // If an error was catch, we send an informative error message back to the controller
            throw $e;
        }
    }

    private function buildQuery(Produit $produit): string
    {
        $query = "UPDATE T_PRODUIT SET ";

        if (!empty($produit->getProductName()))
            $query .= "name = :name, ";

        if (!empty($produit->getDescription()))
            $query .= "description = :description, ";

        if (!empty($produit->getPrix()))
            $query .= "prix = :prix ";

        $query = rtrim($query, ", ");
        $query .= " WHERE id = :id";

        return $query;
    }
}
