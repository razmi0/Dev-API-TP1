<?php

namespace Model\Dao;

use PDO;
use PDOException;
use Utils\Response as Response;



class Connection
{

    public $pdo = null;
    private $host = "localhost:3306";
    private $username = "root";
    private $password = "";
    private $db_name = "db_labrest";


    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $response = new Response(500, "Erreur interne", []);
            $response->send();
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function closeConnection()
    {
        $this->pdo = null;
    }
}
