<?php

/**
 * 
 * DELETE : /api/v1/supprimer.php file
 * path : /api/v1.0/produit/delete
 */

require_once "Autoloader.php";

use Curl\Curl as Session;
use Curl\CurlTest as Test;

// The minimum and maximum id
const MIN_ID = 1;
const MAX_ID = 200;

// The random id
$random_id = rand(MIN_ID, MAX_ID);

// The data to send
$data = ["id" => $random_id];

// The curl object and parameters
$curl = new Session(
    [
        "endpoint" => "delete",
        "options" => [
            "method" => "DELETE",
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
    $http_code === 200 || // OK delete event
    $http_code === 204; // No content no delete event

// Test if the response code is 200 (OK) or 204 (No content)
Test::assert($condition, "The response code must be 200 or 204 and was $http_code");
