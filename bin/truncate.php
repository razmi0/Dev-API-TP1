<?php


/**
 * 
 * This script truncates the database table for the given DAO.
 * Use it in conjontion with migrate.js to repopulate the database.
 * 
 */

use Model\Dao\Connection;

require_once "./vendor/autoload.php";


try {

    // Create a new Connection object
    // --
    $dao = new Connection();

    // Get the PDO object
    // --
    $pdo = $dao->getPDO();

    // Count the number of rows in the table
    // --
    $count = "SELECT COUNT(*) FROM " . $dao->getTableName();

    // Set the query to truncate the table
    // --
    $truncate = "TRUNCATE TABLE " . $dao->getTableName();

    // Prepare the query
    // --
    $stmt_count = $pdo->prepare($count);
    $stmt_truncate = $pdo->prepare($truncate);

    // Execute the query
    // --
    $before_count = $stmt_count->execute();
    $truncate = $stmt_truncate->execute();
    $after_count = $stmt_count->execute();

    // Log the response in the php_error.log file
    // --
    error_log("Table " . $dao->getTableName() . " count before: " . $before_count);
    error_log("Table " . $dao->getTableName() . " truncated: " . $truncate);
    error_log("Table " . $dao->getTableName() . " count after: " . $after_count);

    // Close the connection
    // --
    $dao->closeConnection();
} catch (PDOException $e) {
    throw $e;
}
