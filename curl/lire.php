<?php

/**
 * 
 * DELETE : /api/v1/supprimer.php endpoint 
 */

require_once "Autoloader.php";

use Curl\Curl;

$limit = 10;
$curl = new Curl(
    [
        "endpoint" => "read_all",
        "options" => [
            "method" => "GET",
            "data" => Curl::encodeData(["limit" => $limit])
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Print the stored results
$curl->print_results();
