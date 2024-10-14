<?php

namespace Model\Dao;

use PDO;
use PDOException;
use Utils\Error;



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
            $error = new Error();
            $error
                ->setCode(500)
                ->setError("Service non disponible")
                ->setLocation("model/dao/Connection.php");
            throw $error;
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

    public function setPDOAttributes()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $this;
    }
}
