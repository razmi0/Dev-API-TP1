<?php

namespace Model\Dao;

use PDO;
use PDOException;


/**
 * Class Connection
 *
 * This class is responsible for managing the database connection.
 * It provides methods to establish and close the connection to the database.
 *
 * @method PDO getConnection() Returns the PDO instance for database operations.
 * @method exec($query) Executes the given query and returns the result.
 */
class Connection {
    /**
     * @access private
     * @var PDO
     */
    private $connection = null;
    private $host = "localhost:3306";
    private $username = "root";
    private $password = "";
    private $db_name = "db_labrest";


    public function __construct() {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    private function closeConnection() {
        $this->connection = null;
    }

    public function exec($query) {
        try {
            $prepared = $this->connection->prepare($query);
            $prepared->execute();
            $result = $prepared->fetchAll();
            // var_dump($result);
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }

        $this->closeConnection();

        return $result;
    }


}

