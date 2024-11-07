<?php

/**
 * 
 * PUT : /api/v1/modifier.php file
 * path : /api/v1.0/produit/update
 */

require_once "vendor/autoload.php";

use Curl\Session;
use Curl\Test as Test;

const MIN_ID = 1;
const MAX_ID = 200;


// The data to send
$data = [
    "id" => rand(MIN_ID, MAX_ID),
    "name" => "Product name",
    "description" => "Product description",
    "prix" => rand(1, 1000)
];

// The curl object and parameters
$curl = new Session(
    [
        "endpoint" => "update",
        "options" => [
            "method" => "PUT",
            "data" => Session::encodeData($data)
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();


// Get the response http code
$http_code = $curl->getCode();

// Define condition. 
$condition =
    $http_code === 200 || // OK update event
    $http_code === 204; // No content no update event


// Test if the id is the same as the one sent
Test::assert($condition, "The response code must be 200 or 204 and was $http_code");
