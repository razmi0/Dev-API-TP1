<?php





namespace Model\Dao;

use Exception;
use PDO;

abstract class AbstractDao
{
    protected ?PDO $pdo = null;

    public function __construct(private Connection $connection)
    {
        /**
         * as we can't inforce child constants implementation with traits or interfaces in PHP 8.2.4 without hacky workarounds
         * we will throw an exception if the child class does not define the ENDPOINT_METHOD constant
         * @see https://stackoverflow.com/questions/10368620/abstract-constants-in-php-force-a-child-class-to-define-a-constant
         */
        if (!defined('static::TABLE_NAME')) {
            throw new Exception("TABLE_NAME must be defined in the child class");
        }
        $this->pdo = $this->connection->getPDO();
    }

    public function closeConnection(): self
    {
        $this->connection->closeConnection();
        return $this;
    }
}
