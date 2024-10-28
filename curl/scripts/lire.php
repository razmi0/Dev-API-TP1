<?php


/**
 * 
 * GET : /api/v1/supprimer.php file 
 * path : /api/v1.0/produit/list
 */

require_once "Autoloader.php";

use Curl\Curl as Session;
use Curl\CurlTest as Test;
use Utils\Console;

var_dump(sprintf(Console::COLORS['green'], "The response is OK"));


$limit = 10;
$curl = new Session(
    [
        "endpoint" => "read_all",
        "options" => [
            "method" => "GET",
            "data" => Session::encodeData(["limit" => $limit])
        ]
    ]

);

// Execute the configured curl session and close it
$curl->executeAndClose();

// Get the products array
$products = $curl->getResult()["data"]["products"];

// Get the size of the array
$size = count($products);

// Declare a condition ( an array of $limit elements )
$condition = is_array($products) && $size === $limit;


// Test the condition
Test::assert($condition, "Must be an array of $limit elements, currently $size", $products);
