<?php

/**
 * 
 * DELETE : /api/v1/supprimer.php endpoint 
 */
const URL = "http://localhost/TP1/api/v1/supprimer.php";
const MIN_ID = 1;
const MAX_ID = 200;

// Generate a random id between 1 and 200 and encode it as a json string
$data = json_encode(["id" => (string)rand(MIN_ID, MAX_ID)]);

// Set the headers
$headers = [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
];

// Initialize a new curl session
$ch = curl_init(URL);

// Set the options for the curl session
// CURLOPT_CUSTOMREQUEST : The HTTP method to use
// CURLOPT_RETURNTRANSFER : Return the response as a string
// CURLOPT_POSTFIELDS : The data to send
// CURLOPT_HTTPHEADER : The headers to send
$allOptSuccess = curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => "DELETE",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => $headers,
]);

// If one of the options failed, print the error
if (!$allOptSuccess) {
    echo "A cURL option was not set : " . curl_error($ch);
}

$response = curl_exec($ch);

// If the response is false, print the error
if ($response === false) {
    echo "An error occur in the API" . curl_error($ch);
}

// Print the response
var_export(json_decode($response, true));

// Close the curl session
curl_close($ch);
