<?php

namespace Model\Dao;

use Exception;
use Model\{Entity\Token, Dao\Connection};
use HTTP\Error as Error;
use PDO;

// T_TOKEN columns : token_id, token, token_hash, user_id, created_at, updated_at

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
     * @param Token $token
     * @throws Error 
     * @return int $insertedId
     */
    public function create(Token $token): int | false
    {
        try {

            // sql query
            // in the table T_TOKEN, we insert the token, token_hash and user_id (foreign key)
            $sql = "INSERT INTO T_TOKEN (token, token_hash, user_id) VALUES (:token, :token_hash, :user_id)";

            // Prepare statement
            $stmt = $this->pdo->prepare($sql);

            // bind values
            // --
            $stmt->bindParam(":token", $token->getToken());

            $stmt->bindParam(":token_hash", $token->getTokenHash());

            $stmt->bindParam(":user_id", $token->getUserId(), PDO::PARAM_INT);

            $stmt->execute();

            $insertedId = $this->pdo->lastInsertId();

            // if the id is not set, (no token created)we return false
            if (!$insertedId) {

                return false;
            }

            // return the id of the inserted token
            return (int)$insertedId;
        } catch (Exception $e) {

            error_log($e->getMessage());

            throw Error::HTTP500("Erreur interne");
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

            throw Error::HTTP500("Erreur interne");
        } finally {

            $this->connection->closeConnection();
        }
    }
}
