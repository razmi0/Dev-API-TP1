<?php

namespace Model\Dao;

use PDO;
use PDOException;



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
            http_response_code(500);
            error_log($e->getMessage());
            echo json_encode(["message" => "Erreur interne : "]);
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
