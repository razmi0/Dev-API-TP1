<?php

namespace Model\Dao;

use Model\Entities\Produit as Produit;
use Model\Dao\Connection as Connection;


class ProduitDao {
    private $table_name  = "T_PRODUIT";


    public function getTableName() {
        return $this->table_name;
    }

    public function create($produit){
        
    }


    /**
     * @return Produit[]
     */
    static function findAll(){
        $connection = new Connection();
        $query = "SELECT * FROM T_PRODUIT";
        $result = $connection->exec($query);
        return $result;
    }

    

    public function findById($id){}

    public function delete( $id ){}

    public function update( $id ){}
}