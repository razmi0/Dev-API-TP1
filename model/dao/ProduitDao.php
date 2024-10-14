<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use Model\Dao\Connection as Connection;
use Model\Services\ProduitService as ProduitService;
use Utils\Error as Error;
use PDO;



class ProduitDao
{
    private $pdo;
    private $produitService;
    private $error;

    public function __construct()
    {
        try {

            $connection = new Connection();
            $this->pdo = $connection->setPDOAttributes()->getPDO();
            $this->produitService = new ProduitService();
            $this->error = new Error();
            $this->error
                ->setCode(503)
                ->setError("Impossible de traiter la requete")
                ->setLocation("model/dao/ProduitDao.php");
        } catch (Error $e) {
            throw $e;
        }
    }

    private function bindParams($produit, $prepared)
    {
        $name = $produit->getProductName();
        $description = $produit->getDescription();
        $prix = $produit->getPrix();
        $date = $produit->getDateCreation();

        $prepared->bindParam(':name', $name, PDO::PARAM_STR);
        $prepared->bindParam(':description', $description, PDO::PARAM_STR);
        $prepared->bindParam(':prix', $prix, PDO::PARAM_STR);
        $prepared->bindParam(':date', $date, PDO::PARAM_STR);

        return $prepared;
    }




    /**
     * @param Produit $content
     * @throws Error
     * @return array ["id" => $pdo->lastInsertId()]
     */
    public function create($content)
    {
        $this->error->setLocation("model/dao/ProduitDao.php :: create");


        try {
            // Service step ( validate the data and create the product object )
            // --
            $produit = $this->produitService->createProduit($content);

            // Database Access step ( build the query, prepare it, execute it and return the result )
            // --
            $query = "INSERT INTO T_PRODUIT (name, description, prix, date_creation) VALUES (:name, :description, :prix, :date)";

            // Verify the preparation of the query
            // --
            $prepared = $this->pdo->prepare($query);
            if (!$prepared) {
                throw $this->error;
            }

            // Bind the parameters
            // --

            $prepared = $this->bindParams($produit, $prepared);

            // Verify the execution of the query
            // --
            $stmt = $prepared->execute();
            if (!$stmt) {
                throw $this->error;
            }

            // If all went good, we will return the id of the last inserted product to the controller
            // --
            $prepared->fetchAll();

            // We return the last inserted id in db for user convenience
            //--

            return ["id" => $this->pdo->lastInsertId()];
        } catch (Error $e) {
            // If an error was catch, we send an informative error message back to the controller
            // --
            throw $e;
        }
    }


    /**
     * @throws Error
     * @return Produit[]
     */
    public function findAll()
    {
        // Connection step ( open connection to the database via PDO instantiation and set PDO attributes )
        // --
        try {
            $connection = new Connection();
        } catch (Error $e) {
            throw $e;
        }
        $pdo = $connection->getPDO();

        // No service step here, we are just fetching data from the database
        // --

        // Build the query
        // --
        $query = "SELECT * FROM T_PRODUIT ORDER BY date_creation DESC";

        try {
            // Set PDO attributes
            // --
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // Verify the preparation of the query
            // --
            $prepared = $pdo->prepare($query);
            if (!$prepared) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: findAll");
                throw $error;
            }

            // Verify the execution of the query
            // --
            $stmt = $prepared->execute();
            if (!$stmt) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: findAll");
                throw $error;
            }
            $result = $prepared->fetchAll();

            // If no product was found, we send a response with a 404 status code and an error message
            // --
            if (count($result) == 0) {
                $error = new Error();
                $error->setCode(404)->setError("Aucun produit trouvé")->setLocation("model/dao/ProduitDao.php :: findAll");
                throw $error;
            }

            // If all went good, we will return the result
            // --
            return $result;
        } catch (Error $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            // --
            throw $e;
        }
    }

    public function findById($id)
    {
        // Connection step ( open connection to the database via PDO instantiation and set PDO attributes )
        // --

        try {
            $connection = new Connection();
        } catch (Error $e) {
            throw $e;
        }
        $pdo = $connection->getPDO();

        // No service step here, we are just fetching data from the database
        // --

        // Build the query
        // --
        $query = "SELECT * FROM T_PRODUIT WHERE id = :id";

        try {
            // Set PDO attributes
            // --
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // Verify the preparation of the query
            // --
            $prepared = $pdo->prepare($query);
            if (!$prepared) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: findById");
                throw $error;
            }

            // Bind the parameters
            // --
            $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            // Verify the execution of the query
            // --
            $stmt = $prepared->execute();
            if (!$stmt) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: findById");
                throw $error;
            }
            $result = $prepared->fetchAll();

            // If no product was found, we send a response with a 404 status code and an error message
            // --
            if (count($result) == 0) {
                $error = new Error();
                $error->setCode(404)->setError("Aucun produit trouvé")->setLocation("model/dao/ProduitDao.php :: findById");
                throw $error;
            }

            // If all went good, we will return the result
            // --
            return $result;
        } catch (Error $e) {
            // If an error was catch, we send a response with a 500 status code and an error message
            // --
            throw $e;
        }
    }

    public function deleteById($id)
    {
        // Connection step ( open connection to the database via PDO instantiation and set PDO attributes )
        // --

        try {
            $connection = new Connection();
        } catch (Error $e) {
            throw $e;
        }
        $pdo = $connection->getPDO();

        // No service step here, we are just fetching data from the database
        // --

        // Build the query
        // --
        $query = "DELETE FROM T_PRODUIT WHERE id = :id";

        try {
            // Set PDO attributes
            // --
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // Verify the preparation of the query
            // --
            $prepared = $pdo->prepare($query);
            if (!$prepared) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: deleteById")->setMessage("Erreur lors de la suppression du produit");
                throw $error;
            }

            // Bind the parameters
            // --
            $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            // Verify the execution of the query
            // --
            $stmt = $prepared->execute();
            if (!$stmt) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: deleteById")->setMessage("Erreur lors de la suppression du produit");
                throw $error;
            }

            $affectedRows = $prepared->rowCount();

            // If no product was found, we send a 204 with no content in response body as HTTP specification states
            // The error message is sent to php_error.log
            // --
            if ($affectedRows == 0) {
                $error = new Error();
                $error
                    ->setCode(204)
                    ->setError("Ce produit n'existe pas")
                    ->setMessage("L'utilisateur a tenté de supprimer un produit qui n'existe pas")
                    ->setData(["id" => $id]);
                throw $error;
            }
        } catch (Error $e) {
            throw $e;
        }
    }

    public function update($content)
    {
        // Connection step ( open connection to the database via PDO instantiation and set PDO attributes )
        // --

        try {
            $connection = new Connection();
        } catch (Error $e) {
            throw $e;
        }
        $pdo = $connection->getPDO();

        // Service step ( validate the data and create the product object )
        // --
        try {
            $produitService = new ProduitService();
            $produit = $produitService->createProduit($content);
        } catch (Error $e) {
            throw $e;
        }

        // Database Access step ( build the query, prepare it, execute it and return the result )
        // --
        $query = "UPDATE T_PRODUIT SET name = :name, description = :description, prix = :prix WHERE id = :id";

        try {

            // Set PDO attributes
            // --
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // Verify the preparation of the query
            // --
            $prepared = $pdo->prepare($query);
            if (!$prepared) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: update");
                throw $error;
            }

            $name = $produit->getProductName();
            $description = $produit->getDescription();
            $prix = $produit->getPrix();


            // Bind the parameters
            // --
            $prepared->bindParam(':name', $name, PDO::PARAM_STR);
            $prepared->bindParam(':description', $description, PDO::PARAM_STR);
            $prepared->bindParam(':prix', $prix, PDO::PARAM_STR);
            $prepared->bindParam(':id', $content->id, PDO::PARAM_INT);

            // Verify the execution of the query
            // --
            $stmt = $prepared->execute();
            if (!$stmt) {
                $error = new Error();
                $error->setCode(503)->setError("Service non disponible")->setLocation("model/dao/ProduitDao.php :: update");
                throw $error;
            }
            $affectedRows = $prepared->rowCount();

            if ($affectedRows == 0) {
                $error = new Error();
                $error
                    ->setCode(202)
                    ->setError("Aucune modification")
                    ->setMessage("Aucune modification n'a été apportée à ce produit")
                    ->setData(["id" => $content->id])
                    ->setLocation("model/dao/ProduitDao.php :: update");
                throw $error;
            }

            // If all went good, we will return the id of the last inserted product to the controller
            // --
            return ["id" => $content->id];
        } catch (Error $e) {
            // If an error was catch, we send an informative error message back to the controller
            // --
            throw $e;
        }
    }
}
