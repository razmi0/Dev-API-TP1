<?php

/**
 * 
 * GET : /api/v1/supprimer.php file
 * path : /api/v1.0/produit/delete
 */

require_once "vendor/autoload.php";

use Curl\Curl as Session;
use Curl\CurlTest as Test;

// The minimum and maximum id
const MIN_ID = 1;
const MAX_ID = 200;

// The random ids array length
$ids_length = rand(1, 10);

// The random ids array filled with zeros
$ids_array =  array_fill(0, $ids_length, 0);

// The random ids array filled with random ids
$random_ids = array_map(fn() => rand(MIN_ID, MAX_ID), $ids_array);

// The data to send
$data = ["id" => $random_ids];

// The curl object and parameters
$curl = new Session(
    [
        "endpoint" => "read_many",
        "options" => [
            "method" => "GET",
            "data" => Session::encodeData($data)
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Get the products
$products = $curl->getResult()["data"]["products"];

// Get the size of the array
$size = count($products);

// Test if the product is an array of ids_length elements
Test::assert($size === $ids_length, "Must be an array of $ids_length elements, currently $size", $products);
