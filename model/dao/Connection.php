<?php

namespace Model\Dao;

use PDO;
use PDOException;
use HTTP\Error;


/**
 * Class Connection
 * 
 * Has some presets for dev PDO connection 
 * - **getPDO** : return the PDO object
 * - **closeConnection** : close the connection
 * - **setDbName** : set the database name
 * - **__construct** : create a new PDO connection
 */
class Connection
{

    private $pdo = null;
    private $host = "localhost";
    private $port = 3306;
    private $username = "root";
    private $password = "";
    private $db_name = "db_labrest";


    public function __construct()
    {
        try {
            $this->pdo = self::setPDOAttributes(
                new PDO(
                    dsn: "mysql:host=$this->host:$this->port;dbname=$this->db_name",
                    username: $this->username,
                    password: $this->password
                )
            );
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Error::HTTP503("Service non disponible");
        }
    }

    public function setDbName($db_name): self
    {
        $this->db_name = $db_name;
        return $this;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function closeConnection(): self
    {
        $this->pdo = null;
        return $this;
    }

    private static function setPDOAttributes(PDO $pdo): PDO
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }
}
