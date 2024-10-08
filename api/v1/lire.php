<?php

require_once "../../Autoloader.php";

use Model\Dao\ProduitDao as Dao;
class Lire {
    private $produitDao;

    public function __construct() {
        $this->produitDao = new Dao();
    }

    public function getAll() {
        $res = $this->produitDao->findAll();
        return $res;
    }
}