<?php

namespace Model\Dao;

use Exception;
use Model\{Entity\User, Dao\Connection, Dao\AbstractDao};
use HTTP\Error as Error;

/**
 * Class UserDao
 * 
 * This class is a DAO for the user entity
 * - **create** : create a user
 * - **find** : find a user by a field
 */
class UserDao extends AbstractDao
{
    public const TABLE_NAME = "T_USER";

    public function __construct(private Connection $connection)
    {
        parent::__construct($connection);
    }

    /**
     * Create a user
     * 
     * @param User $user
     * @throws Exception
     * @return int|false $insertedId
     */
    public function create(User $user): int|false
    {
        try {
            $sql = "INSERT INTO " . self::TABLE_NAME . " (username, email, password_hash) VALUES (:username, :email, :password_hash)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(":username", $user->getUsername());
            $stmt->bindParam(":email", $user->getEmail());
            $stmt->bindParam(":password_hash", $user->getPasswordHash());

            $stmt->execute();
            $insertedId = $this->pdo->lastInsertId();

            return $insertedId ? (int)$insertedId : false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            Error::HTTP500("Erreur interne");
        } finally {
            $this->closeConnection();
        }
    }

    /**
     * Find a user by a field
     * 
     * @param string $field
     * @param string $value
     * @throws Exception 
     * @return User|false $user
     */
    public function find(string $field, string $value): User|false
    {
        try {
            $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE $field = :value";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(":value", $value);
            $stmt->execute();

            $data = $stmt->fetch();

            return $data ? User::make($data) : false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            Error::HTTP500("Erreur interne");
        } finally {
            $this->closeConnection();
        }
    }
}
