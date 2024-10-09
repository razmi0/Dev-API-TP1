<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use Model\Dao\Connection as Connection;
use PDO;

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
    public static function create($content)
    {
        $connection = new Connection();

        $produit = new Produit();
        $produit->setName($content->product_name);
        $produit->setDescription($content->description);
        $produit->setPrix($content->prix);
        $produit->setDateCreation(date("Y-m-d H:i:s"));

        $query = "INSERT INTO T_PRODUIT (product_name, description, prix, date_creation) VALUES (:name, :description, :prix, :date)";

        $prepared = $connection->getConnection()->prepare($query);
        $prepared->bindParam(':name', $produit->getProductName(), PDO::PARAM_STR);
        $prepared->bindParam(':description', $produit->getDescription(), PDO::PARAM_STR);
        $prepared->bindParam(':prix', $produit->getPrix(), PDO::PARAM_STR);
        $prepared->bindParam(':date', $produit->getDateCreation(), PDO::PARAM_STR);

        $connection->safeExecute($prepared);
        var_dump($produit);

        return $produit;
    }


    /**
     * @return Produit[]
     */
    static function findAll()
    {
        $connection = new Connection();
        $query = "SELECT * FROM T_PRODUIT";
        $prepared = $connection->getConnection()->prepare($query);
        $result = $connection->safeExecute($prepared);
        return $result;
    }



    public function findById($id) {}

    public function delete($id) {}

    public function update($id) {}
}
