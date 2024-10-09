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
            http_response_code(503);
            echo json_encode(["message" => "Service non disponible : " . $e->getMessage()]);
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    private function closeConnection() {
        $this->connection = null;
    }

    public function safeExecute($prepared) {
        try {
            $prepared->execute();
            $result = $prepared->fetchAll();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erreur lors de l'envoi de la requête : " . $e->getMessage()]);
            die();
        }

        $this->closeConnection();

        return $result;
    }


}

