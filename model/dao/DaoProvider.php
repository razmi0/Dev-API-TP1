<?php

namespace Model\Dao;

use Model\Dao\{Connection, ProductDao, TokenDao, UserDao};
use Utils\Patterns\ISingleton;
use Utils\Patterns\SingletonAbstract;

interface IDaoProvider extends ISingleton
{
    public static function getConnection(): Connection;
    public static function getProductDao(): ProductDao;
    public static function getTokenDao(): TokenDao;
    public static function getUserDao(): UserDao;
}

/**
 * Class DaoProvider
 * 
 * This class is a singleton factory for all the DAOs in the project
 * @static **getInstance**: returns the instance of the class
 * @static **getConnection**: returns the connection
 * @static **getProductDao**: returns the product DAO
 * @static **getTokenDao**: returns the token DAO
 * @static **getUserDao**: returns the user DAO
 */
final class DaoProvider extends SingletonAbstract implements IDaoProvider
{

    private static ?DaoProvider $instance = null;
    private static ?Connection $connection = null;
    private static ?ProductDao $productDao = null;
    private static ?TokenDao $tokenDao = null;
    private static ?UserDao $userDao = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getConnection(): Connection
    {
        if (self::$connection === null) {
            self::$connection = new Connection();
        }

        return self::$connection;
    }

    public static function getProductDao(): ProductDao
    {
        if (self::$productDao === null) {
            $connection = self::getConnection();
            self::$productDao = new ProductDao($connection);
        }

        return self::$productDao;
    }

    public static function getTokenDao(): TokenDao
    {
        if (self::$tokenDao === null) {
            $connection = self::getConnection();
            self::$tokenDao = new TokenDao($connection);
        }

        return self::$tokenDao;
    }

    public static function getUserDao(): UserDao
    {
        if (self::$userDao === null) {
            $connection = self::getConnection();
            self::$userDao = new UserDao($connection);
        }

        return self::$userDao;
    }
}
