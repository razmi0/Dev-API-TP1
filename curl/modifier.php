<?php

/**
 * 
 * PUT : /api/v1/modifier.php endpoint
 */

require_once "Autoloader.php";

use Curl\Curl;

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
$curl = new Curl(
    [
        "endpoint" => "update",
        "options" => [
            "method" => "PUT",
            "data" => Curl::encodeData($data)
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Print the stored results
$curl->print_results();
