<?php

/**
 * 
 * PUT : /api/v1/modifier.php file
 * path : /api/v1.0/produit/update
 */

require_once "Autoloader.php";

use Curl\Curl as Session;
use Curl\CurlTest as Test;

const MIN_ID = 1;
const MAX_ID = 200;

// The random id, name, description and prix
$id = rand(MIN_ID, MAX_ID);
$name = "Product name";
$description = "Product description";
$prix = rand(1, 1000);

// The data to send
$data = [
    "id" => $id,
    "name" => $name,
    "description" => $description,
    "prix" => $prix
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

// Get the id returned on this endpoint
$returned_id = $curl->getResult()["data"]["id"];

// Test if the id is the same as the one sent
Test::assert($id === $returned_id, "The id sent is $id, the id returned is $returned_id");
