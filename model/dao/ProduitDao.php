<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use Model\Dao\Connection as Connection;
use HTTP\Error as Error;
use PDO;



class ProduitDao
{
    private $pdo;
    private $error;

    public function __construct()
    {
        try {

            $connection = new Connection();
            $this->error = new Error();
            $this->pdo = $connection->setPDOAttributes()->getPDO();
            $this->error
                ->setCode(503)
                ->setError("Impossible de traiter la requete")
                ->setLocation("model/dao/ProduitDao.php");
        } catch (Error $e) {
            $this->error->setLocation("model/dao/ProduitDao.php-> __construct");
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
            $prepared->bindParam(':prix', $prix, PDO::PARAM_STR);
            $prepared->bindParam(':date', $date, PDO::PARAM_STR);
        } catch (Error $e) {
            $this->error->setLocation("model/dao/ProduitDao.php-> bindParamsCreate");
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
            $this->error->setLocation("model/dao/ProduitDao.php-> create");
            // Database Access step ( build the query, prepare it, execute it and return the result )
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

            // If all went good, we will return the id of the last inserted product to the controller
            $insertedId = $this->pdo->lastInsertId();

            // We return the last inserted id in db for user convenience
            //--
            return $insertedId;
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
            $this->error->setLocation("model/dao/ProduitDao.php-> findAll");

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
                $this->error
                    ->setCode(404)
                    ->setError("La base de données est vide");
                throw $this->error;
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
            $this->error->setLocation("model/dao/ProduitDao.php-> findById");

            // Build the query
            $query = "SELECT * FROM T_PRODUIT WHERE id = :id";
            // Set PDO attributes
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                $this->error
                    ->setCode(404)
                    ->setError("Aucun produit trouvé")
                    ->setLocation("model/dao/ProduitDao.php-> findById");
                throw $this->error;
            }

            // Create a new product object
            $produit = Produit::make($result);

            // If all went good, we will return the product to the controller
            return $produit;
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
            $this->error
                ->setLocation("model/dao/ProduitDao.php -> deleteById");
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
                throw $this->error->setCode(500)
                    ->setError("Erreur lors de la suppression du produit");
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
            $prepared->bindParam(':name', $name, PDO::PARAM_STR);
            $prepared->bindParam(':description', $description, PDO::PARAM_STR);
            $prepared->bindParam(':prix', $prix, PDO::PARAM_INT);
        } catch (Error $e) {
            $this->error->setLocation("model/dao/ProduitDao.php-> bindParamsUpdate");
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
            $this->error
                ->setLocation("model/dao/ProduitDao.php-> update")
                ->setMessage("Erreur lors de la modification du produit");

            // Database Access step ( build the query, prepare it, execute it and return the result )
            $query = "UPDATE T_PRODUIT SET name = :name, description = :description, prix = :prix WHERE id = :id";

            // Verify the preparation of the query
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Bind the parameters
            $prepared = $this->bindParamsUpdate($produit, $prepared);

            // Verify the execution of the query
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error;
            }
            $affectedRows = $prepared->rowCount();

            if ($affectedRows == 0) {
                $this->error
                    ->setCode(202)
                    ->setError("Aucune modification")
                    ->setMessage("Aucune modification n'a été apportée à ce produit")
                    ->setData(["id" => $produit->getId()]);
                throw $this->error;
            }

            // If all went good, we will return the id of the last inserted product to the controller
            return $produit->id;
        } catch (Error $e) {
            // If an error was catch, we send an informative error message back to the controller
            throw $e;
        }
    }
}
