<?php

namespace Model\Dao;

use Exception;
use Model\{Entity\Token, Dao\Connection};
use HTTP\Error as Error;
use PDO;

// T_TOKEN columns : token_id, jwt_value, user_id, created_at, updated_at

class TokenDao
{
    private ?PDO $pdo = null;
    private ?Connection $connection = null;

    public function __construct(Connection $connection)
    {

        $this->connection = $connection;
        $this->pdo = $this->connection->getPDO();
    }

    /**
     * Replace the token in the database if it already exists or create a new one if not
     * 
     * @param Token $token
     * @throws Error 
     * @return int | false $insertedId
     */
    public function create(Token $token): int | false
    {
        try {

            // in the table T_TOKEN, we insert the token, jwt_value and user_id (foreign key)
            $sql = "INSERT INTO T_TOKEN (jwt_value, user_id) VALUES (:jwt_value, :user_id) ON DUPLICATE KEY UPDATE jwt_value = :updated_jwt_value";

            // Prepare statement
            $stmt = $this->pdo->prepare($sql);

            // bind values
            // --

            $token_value = $token->getTokenValue();

            $stmt->bindParam(":jwt_value", $token_value);

            $stmt->bindParam(":user_id", $token->getUserId(), PDO::PARAM_INT);

            $stmt->bindParam(":updated_jwt_value", $token_value);

            $stmt->execute();

            $insertedId = $this->pdo->lastInsertId();

            // if the id is not set, (no token created) we return false
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
     * @param string $id
     * @throws Error 
     * @return Token $token
     */
    public function find(string $field, string $value): Token
    {
        try {
            $sql = "SELECT * FROM T_TOKEN WHERE $field = :value";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(":value", $value);

            $stmt->execute();

            $data = $stmt->fetch();

            if (!$data) {
                Error::HTTP404("Token non trouvé", ["token" => $value]);
            }

            return Token::make($data);
        } catch (Exception $e) {

            error_log($e->getMessage());

            Error::HTTP500("Erreur interne");
        } finally {

            $this->connection->closeConnection();
        }
    }
}
