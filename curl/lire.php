<?php

/**
 * 
 * DELETE : /api/v1/supprimer.php endpoint 
 */

require_once "Autoloader.php";

use Curl\Curl;

$curl = new Curl(
    [
        "endpoint" => "read_all",
        "options" => [
            "method" => "GET",
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Print the stored results
$curl->print_results();
