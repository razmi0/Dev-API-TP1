<?php

/**
 * 
 * POST : /api/v1/creer.php endpoint
 */

require_once "Autoloader.php";

use Curl\Curl;

// The minimum and maximum id
$name = "Product name";
$description = "Product description";
$prix = rand(1, 1000);

// The data to send
$data = [
    "name" => $name,
    "description" => $description,
    "prix" => $prix
];

// The curl object and parameters
$curl = new Curl(
    [
        "endpoint" => "create",
        "options" => [
            "method" => "POST",
            "data" => Curl::encodeData($data)
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Print the stored results
$curl->print_results();
