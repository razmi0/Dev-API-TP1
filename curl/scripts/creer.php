<?php

/**
 * 
 * POST : /api/v1/creer.php file
 * path : /api/v1.0/produit/new
 */

require_once "vendor/autoload.php";

use Curl\Session;
use Curl\Test as Test;

const MAX_PRICE = 1;
const MIN_PRICE = 1000;

// The data to send
$data = [
    "name" => "Product name",
    "description" => "Product description",
    "prix" => (float)(rand(MAX_PRICE, MIN_PRICE)) + 0.99,
];


/**
 * curl is an object configuration for the curl session
 * @see Curl/Curl.php
 */
$curl = new Session(
    [
        "endpoint" => "create",
        "options" => [
            "method" => "POST",
            "data" => Session::encodeData($data)
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Get the code response
$http_code = $curl->getCode();

// Get the id returned on this endpoint
$returned_id = $curl->getResult()["data"]["id"];

// Define condition
$condition = $http_code === 201 && $returned_id !== null;

/**
 * Test the condition
 * @see Curl/Test.php
 */
Test::assert(
    $returned_id !== null && $http_code === 201,
    "Must return an id : $returned_id and a status code $http_code",
    $returned_id
);
