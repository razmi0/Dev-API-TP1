<?php

namespace Model\Dao;

use Model\Entities\Product as Product;
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

    /**
     * @param Product $produit
     * @throws Error 
     * @return string $insertedId
     */
    public function create($produit)
    {
        try {
            // Setup the error message
            $this->error->setLocation("model/dao/ProductDao.php-> create");

            // Build the query
            $query = "INSERT INTO T_PRODUIT (name, description, prix, date_creation)";
            $query .= " VALUES (:name, :description, :prix, :date_creation)";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Bind the parameters
            $id = $produit->getId();
            $name = $produit->getProductName();
            $description = $produit->getDescription();
            $prix = $produit->getPrix();
            $date_creation = $produit->getDateCreation();

            if (!is_null($id))
                $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            // No PARAM_* default to PARAM_STR
            if (!is_null($name))
                $prepared->bindParam(':name', $name);

            if (!is_null($description))
                $prepared->bindParam(':description', $description);

            // we use bindValue here because we want to keep the right type (double) for the price
            if (!is_null($prix))
                $prepared->bindValue(':prix', $prix);

            if (!is_null($date_creation))
                $prepared->bindParam(':date_creation', $date_creation);

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
        $this->connection->closeConnection();
    }


    /**
     * 
     * @description Find all products
     * @throws Error
     * @return Product[]
     * 
     */
    public function findAll(int $limit = null): array
    {

        try {
            $this->error->setLocation("model/dao/ProductDao.php-> findAll");

            // Build the query
            $query = "SELECT * FROM " . $this->connection->getTableName() . " ORDER BY date_creation DESC";
            $query .= $limit ? " LIMIT :limit" : "";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Bind the parameters
            if ($limit) {
                $prepared->bindParam(':limit', $limit, PDO::PARAM_INT);
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
            return array_map(fn($product) => Product::make($product), $products_from_db);
        } catch (Error $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            throw $e;
        }
        $this->connection->closeConnection();
    }


    /**
     * 
     * @param $id
     * @throws Error
     * @return Product
     * 
     */
    public function findById(int $id): Product
    {


        try {
            $this->error->setLocation("model/dao/ProductDao.php-> findById");

            // Build the query
            $query = "SELECT * FROM " . $this->connection->getTableName() . " WHERE id = :id";

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
            return Product::make($result);
        } catch (Error $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            throw $e;
        }
        $this->connection->closeConnection();
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
            $query = "DELETE FROM " . $this->connection->getTableName() . " WHERE id = :id";
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
        $this->connection->closeConnection();
    }


    /**
     * 
     * @param Product $produit
     * @throws Error
     * @return array
     * 
     */
    public function update(Product $produit): int
    {

        try {

            // Get the id of the product
            $id = $produit->getId();
            $name = $produit->getProductName();
            $description = $produit->getDescription();
            $prix = $produit->getPrix();

            // Setup the error message
            $error = ["Erreur lors de la mise à jour du produit", ["id" => $id], "model/dao/ProductDao.php -> update"];

            // Build the query
            $query = "UPDATE " . $this->connection->getTableName() . " SET ";

            if (!empty($name))
                $query .= "name = :name, ";

            if (!empty($description))
                $query .= "description = :description, ";

            if (!empty($prix))
                $query .= "prix = :prix ";

            // Remove the last comma and space
            $query = rtrim($query, ", ");
            $query .= " WHERE id = :id";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error->HTTP500(...$error);
            }

            // Bind the parameters
            $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            if (!empty($name))
                $prepared->bindParam(':name', $name);

            if (!empty($description))
                $prepared->bindParam(':description', $description);

            if (!empty($prix))
                $prepared->bindValue(':prix', $prix);

            // Verify the execution of the query
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error->HTTP500(...$error);
            }
            $affectedRows = $prepared->rowCount();

            if ($affectedRows == 0) {
                throw $this->error->HTTP204("Aucune modification", ["id" => $id]);
            }

            // If all went good, we will return the id of the last inserted product to the controller
            return $id;
        } catch (Error $e) {
            // If an error was catch, we send an informative error message back to the controller
            throw $e;
        }
        $this->connection->closeConnection();
    }

    /**
     * 
     * @param array $ids
     * @throws Error
     * @return Product[]
     * 
     */
    public function findSomeById(array $ids): array
    {
        try {
            $this->error->setLocation("model/dao/ProductDao.php-> findSomeById");

            // Build the query
            $query = "SELECT * FROM " . $this->connection->getTableName() . " WHERE id IN (";

            // We build the query with the number of ids
            $query .= implode(",", array_fill(0, count($ids), "?"));
            $query .= ")";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Verify the execution of the query
            $stmt = $prepared->execute($ids);
            if (!$stmt) {
                throw $this->error;
            }

            // Fetch the result
            $result = $prepared->fetchAll();

            // If no product was found, we send a response with a 404 status code and an error message
            if (!$result) {
                throw $this->error->HTTP404("Aucun produit trouvé", ["ids" => $ids], "model/dao/ProductDao.php-> findSomeById");
            }

            // We map the products from the database to a new array of products entities and return it to the controller
            return Product::makeBulk($result);
        } catch (Error $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            throw $e;
        }
        $this->connection->closeConnection();
    }
}
