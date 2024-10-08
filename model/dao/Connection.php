<?php

namespace Model\Dao;

class Connection {
    private $connection;
    private $host = "localhost:3306";
    private $username = "root";
    private $password = "";
    private $table_name;
    private $db_name = "db_labrest";


    public function __construct($table_name) {
        $this->table_name = $table_name;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        mysqli_close($this->connection);
    }

    public function startConnection() {
        $res = $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

    public function execQuery($query) {
        $res = mysqli_query($this->connection, $query);
        if (!$res) {
            return false;
        } else {
            return $res;
        }
    }


}

