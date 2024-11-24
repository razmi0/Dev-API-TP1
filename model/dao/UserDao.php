<?php

namespace Model\Dao;

use Exception;
use Model\{Entity\User, Dao\Connection};
use HTTP\Error as Error;
use PDO;

// T_USER columns : user_id, username, email, password_hash, created_at, updated_at

class UserDao
{
    private ?PDO $pdo = null;
    private ?Connection $connection = null;

    public function __construct(Connection $connection)
    {

        $this->connection = $connection;
        $this->pdo = $this->connection->getPDO();
    }

    /**
     * Create a user
     * 
     * @param User $user
     * @throws Error 
     * @return int $insertedId
     */
    public function create(User $user): int | false
    {
        try {

            // sql query
            // in the table T_TOKEN, we insert the token, token_hash and user_id (foreign key)
            $sql = "INSERT INTO T_USER (username, email, password_hash) VALUES (:username, :email, :password_hash)";

            // Prepare statement
            $stmt = $this->pdo->prepare($sql);

            // bind values
            // --
            $stmt->bindParam(":username", $user->getUsername());

            $stmt->bindParam(":email", $user->getEmail());

            $stmt->bindParam(":password_hash", $user->getPasswordHash());

            $stmt->execute();

            $insertedId = $this->pdo->lastInsertId();

            // if the id is not set, (no user created) we return false
            if (!$insertedId) {

                return false;
            }

            // return the id of the inserted token
            return (int)$insertedId;
        } catch (Exception $e) {

            error_log($e->getMessage());

            Error::HTTP500("Erreur interne");
        } finally {

            $this->connection->closeConnection();
        }
    }

    /**
     * Find a user by a field
     * 
     * @param string $field
     * @param string $value
     * @throws Error 
     * @return User| false $user
     */
    public function find(string $field, string $value): User | false
    {
        try {
            $sql = "SELECT * FROM T_USER WHERE $field = :value";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(":value", $value);

            $stmt->execute();

            $data = $stmt->fetch();

            if (!$data) {

                return false;
            }

            return User::make($data);
        } catch (Exception $e) {

            error_log($e->getMessage());

            Error::HTTP500("Erreur interne");
        } finally {

            $this->connection->closeConnection();
        }
    }
}
