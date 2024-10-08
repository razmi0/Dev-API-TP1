<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use Model\Dao\Connection as Connection;
use PDO;

class ProduitDao {
    private $table_name  = "T_PRODUIT";


    public function getTableName() {
        return $this->table_name;
    }

    /**
     * @param Produit $content
     */
    public static function create($content){
        $connection = new Connection();

        
        $name = $content['name'];
        var_dump($content);
        $description = $content['description'];
        $prix = $content['prix'];
        $date_creation = $content['date_creation'] ?? date("Y-m-d H:i:s");

        $produit = new Produit($name, $description, $prix, $date_creation);

        $query = "INSERT INTO T_PRODUIT (name, description, prix, date) VALUES (:name, :description, :prix, :date)";

        $prepared = $connection->getConnection()->prepare($query);

        $prepared->bindParam(':name', $name, PDO::PARAM_STR);
        $prepared->bindParam(':description', $description, PDO::PARAM_STR);
        $prepared->bindParam(':prix', $prix, PDO::PARAM_STR);
        $prepared->bindParam(':date', $date_creation, PDO::PARAM_STR);

        $connection->safeExecute($prepared);

        return $produit;




        // $connection->prepare("INSERT INTO T_PRODUIT (name, description, prix, date) VALUES (:name, :description, :prix, :date)");

        
    }


    /**
     * @return Produit[]
     */
    static function findAll(){
        $connection = new Connection();
        $query = "SELECT * FROM T_PRODUIT";
        $prepared = $connection->getConnection()->prepare($query);
        $result = $connection->safeExecute($prepared);
        return $result;
    }

    

    public function findById($id){}

    public function delete( $id ){}

    public function update( $id ){}
}