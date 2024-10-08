<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use \Throwable as Throwable;

// require_once "Autoloader.php";

/**
 * execute sql query (insert, select, update)
 * wait for result
 * instantiate Produit
 * 
* create ($produit) : crée le produit $produit en BD
* findAll () : retourne l’ensemble des produits de la BD
* findById ($id) : retourne le produit correspondant à $id
* delete ($id) : supprime le produit en BD correspondant à $id
    * update ($produit) : modifie le produit en BD à partir de $produit
 */



class ProduitDao {
    private $table_name  = "T_PRODUIT";
    private $connection;


    public function __construct() {
        $this->connection = new Connection($this->table_name);
    }

    public function getTableName() {
        return $this->table_name;
    }

    public function create($produit){
        
    }


    public function findAll(){
        $this->connection->startConnection();
        $query = "SELECT * FROM T_PRODUIT ORDER BY date_creation DESC;";

        try {
            $result = mysqli_query($this->connection->getConnection(),$query)->fetch_all();
        } catch (Throwable $err) {
            $result = $err;
            return $result;
        }

        $this->connection->closeConnection();

        // row[0] = id
        // row[1] = name
        // row[2] = description
        // row[3] = prix
        // row[4] = date_creation
        foreach( $result as $row){
            $produit = [
                "id" => $row[0],
                "name" => $row[1],
                "description" => $row[2],
                "prix" => $row[3],
                "date_creation" => $row[4]
            ];
            $produits[] = $produit;
        }

        return json_encode($produits);
    }

    public function findById($id){}

    public function delete( $id ){}

    public function update( $id ){}
}