<?php

namespace Model\Dao;

use PDO;
use PDOException;
use HTTP\Error;


/**
 * Class Connection
 * 
 * Has some presets for PDO connection and this project's database
 */
class Connection
{

    private $pdo = null;
    private $host = "localhost:3306";
    private $username = "root";
    private $password = "";
    private $db_name = "db_labrest";
    private $table_name = "T_PRODUIT";


    public function __construct(string $table_name = null)
    {
        if ($table_name) {
            $this->table_name = $table_name;
        }
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->setPDOAttributes();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw Error::HTTP503("Service non disponible");
        }
    }

    public function setDbName($db_name)
    {
        $this->db_name = $db_name;
    }


    public function getTableName()
    {
        return $this->table_name;
    }

    public function setTableName($table_name)
    {
        $this->table_name = $table_name;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function closeConnection()
    {
        $this->pdo = null;
    }

    private function setPDOAttributes()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $this;
    }
}
