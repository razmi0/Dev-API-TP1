<?php

use Model\Dao\Connection;

require_once "./Autoloader.php";




try {

    // Create a new Connection object
    // --
    $dao = new Connection();

    // Set the PDO attributes
    // --
    $dao->setPDOAttributes();

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
