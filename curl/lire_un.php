<?php

/**
 * 
 * DELETE : /api/v1/supprimer.php endpoint 
 */

require_once "Autoloader.php";

use Curl\Curl;

// The minimum and maximum id
const MIN_ID = 1;
const MAX_ID = 200;

// The random id
$random_id = rand(MIN_ID, MAX_ID);

// The data to send
$data = ["id" => $random_id];

// The curl object and parameters
$curl = new Curl(
    [
        "endpoint" => "read_one",
        "options" => [
            "method" => "GET",
            "data" => Curl::encodeData($data)
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Print the stored results
$curl->print_results();
