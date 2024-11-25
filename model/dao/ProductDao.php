<?php

namespace Model\Dao;

use Exception;
use Model\{Entity\Product, Dao\Connection, Dao\AbstractDao};
use HTTP\Error as Error;
use PDO;

/**
 * Class ProductDao
 * 
 * This class is a DAO for the product entity
 * - **create** : create a product
 * - **findAll** : find all products
 * - **findById** : find a product by its id
 * - **deleteById** : delete a product by its id
 * - **update** : update a product
 * - **findManyById** : find many products by their ids
 */
class ProductDao extends AbstractDao
{
    public const TABLE_NAME = "T_PRODUIT";

    public function __construct(private Connection $connection)
    {
        parent::__construct($connection);
    }

    /**
     * @param Product $produit
     * @throws Exception 
     * @return string $insertedId
     */
    public function create(Product $produit): string
    {
        try {
            $query = "INSERT INTO T_PRODUIT (name, description, prix, date_creation) VALUES (:name, :description, :prix, :date_creation)";
            $prepared = $this->pdo->prepare($query);

            if (!$prepared) {
                Error::HTTP500("Erreur interne");
            }

            $name = $produit->getProductName();
            $description = $produit->getDescription();
            $prix = $produit->getPrix();
            $date_creation = $produit->getDateCreation();

            $prepared->bindParam(':name', $name);
            $prepared->bindParam(':description', $description);
            $prepared->bindValue(':prix', $prix);
            $prepared->bindParam(':date_creation', $date_creation);

            if (!$prepared->execute()) {
                Error::HTTP500("Erreur interne");
            }

            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeConnection();
        }
    }

    /**
     * @description Find all products
     * @param int $limit
     * @throws Exception
     * @return Product[]
     */
    public function findAll(int $limit = null): array
    {
        try {
            $query = "SELECT * FROM T_PRODUIT ORDER BY date_creation DESC";
            if ($limit) {
                $query .= " LIMIT :limit";
            }

            $prepared = $this->pdo->prepare($query);

            if (!$prepared)
                Error::HTTP500("Erreur interne");


            if ($limit)
                $prepared->bindParam(':limit', $limit, PDO::PARAM_INT);


            if (!$prepared->execute())
                Error::HTTP500("Erreur interne");


            $products_from_db = $prepared->fetchAll();

            if (count($products_from_db) == 0)
                Error::HTTP404("Aucun produit trouvé");


            return array_map(fn($product) => Product::make($product), $products_from_db);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeConnection();
        }
    }

    /**
     * @param int $id
     * @throws Exception
     * @return Product
     */
    public function findById(int $id): Product
    {
        try {
            $query = "SELECT * FROM T_PRODUIT WHERE id = :id";
            $prepared = $this->pdo->prepare($query);

            if (!$prepared)
                Error::HTTP500("Erreur interne");


            $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$prepared->execute())
                Error::HTTP500("Erreur interne");


            $result = $prepared->fetch();

            if (!$result)
                Error::HTTP404("Aucun produit trouvé", ["id" => $id]);


            return Product::make($result);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeConnection();
        }
    }

    /**
     * @description Delete a product by its id
     * @param int $id
     * @throws Exception
     * @return int
     */
    public function deleteById(int $id): int
    {
        try {
            $query = "DELETE FROM T_PRODUIT WHERE id = :id";
            $prepared = $this->pdo->prepare($query);

            if (!$prepared)
                Error::HTTP500("Erreur interne");


            $prepared->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$prepared->execute())
                Error::HTTP500("Erreur interne");


            return $prepared->rowCount();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeConnection();
        }
    }

    /**
     * @param Product $produit
     * @throws Exception
     * @return int
     */
    public function update(Product $produit): int
    {
        try {
            $id = $produit->getId();
            $name = $produit->getProductName();
            $description = $produit->getDescription();
            $prix = $produit->getPrix();

            $query = "UPDATE T_PRODUIT SET ";
            if (!empty($name))
                $query .= "name = :name, ";
            if (!empty($description))
                $query .= "description = :description, ";
            if (!empty($prix))
                $query .= "prix = :prix ";

            $query = rtrim($query, ", ");
            $query .= " WHERE id = :id";

            $prepared = $this->pdo->prepare($query);

            if (!$prepared) {
                Error::HTTP500("Erreur interne", ["id" => $id]);
            }

            $prepared->bindParam(':id', $id, PDO::PARAM_INT);
            if (!empty($name))
                $prepared->bindParam(':name', $name);
            if (!empty($description))
                $prepared->bindParam(':description', $description);
            if (!empty($prix))
                $prepared->bindValue(':prix', $prix);

            if (!$prepared->execute()) {
                Error::HTTP500("Erreur interne", ["id" => $id]);
            }

            return $prepared->rowCount();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeConnection();
        }
    }

    /**
     * @param array $ids
     * @throws Exception
     * @return Product[]
     */
    public function findManyById(array $ids): array
    {
        try {
            $placeholders = implode(",", array_fill(0, count($ids), "?"));
            $query = "SELECT * FROM T_PRODUIT WHERE id IN ($placeholders)";
            $prepared = $this->pdo->prepare($query);

            if (!$prepared)
                Error::HTTP500("Erreur interne");


            if (!$prepared->execute($ids))
                Error::HTTP500("Erreur interne");

            $result = $prepared->fetchAll();

            if (!$result)
                Error::HTTP404("Aucun produit trouvé", ["ids" => $ids]);


            return Product::makeBulk($result);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeConnection();
        }
    }
}
