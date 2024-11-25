<?php

namespace Model\Dao;

use Curl\Test;
use Model\Dao\{Connection, ProductDao, TokenDao, UserDao};
use Utils\Patterns\ISingleton;
use Utils\Patterns\SingletonAbstract;

interface IDaoProvider
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

    private static ?ProductDao $productDao = null;
    private static ?TokenDao $tokenDao = null;
    private static ?UserDao $userDao = null;


    public static function getConnection(): Connection
    {
        return new Connection();
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
