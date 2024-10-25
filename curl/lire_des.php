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

// The random ids array length
$ids_length = rand(1, 10);

// The random ids array filled with zeros
$ids_array =  array_fill(0, $ids_length, 0);

// The random ids array filled with random ids
$random_ids = array_map(fn() => rand(MIN_ID, MAX_ID), $ids_array);

var_export("Random ids array : ");
var_export($random_ids);

// The data to send
$data = ["id" => $random_ids];

// The curl object and parameters
$curl = new Curl(
    [
        "endpoint" => "read_many",
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
