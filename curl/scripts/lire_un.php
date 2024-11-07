<?php

/**
 * 
 * GET : /api/v1/supprimer.php file
 * path : /api/v1.0/produit/listone
 */

require_once "vendor/autoload.php";

use Curl\Curl as Session;
use Curl\CurlTest as Test;

// The minimum and maximum id
const MIN_ID = 1;
const MAX_ID = 10;

// The random id
$random_id = rand(MIN_ID, MAX_ID);

// The data to send
$data = ["id" => $random_id];

// The curl object and parameters
$curl = new Session(
    [
        "endpoint" => "read_one",
        "options" => [
            "method" => "GET",
            "data" => Session::encodeData($data)
        ]
    ]

);

// Execute the curl session and close it
$curl->executeAndClose();

// Get the products
$product = $curl->getResult()["data"];

// Get the size of the array
$size = count($product);

// Test if the product is an array of 1 element
Test::assert($size === 1, "Must be an array of 1 element, currently $size", $product);
