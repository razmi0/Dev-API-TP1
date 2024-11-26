<?php

// Define the base directory for your project
define('BASE_DIR', __DIR__);



// Get the requested URI
$requestUri = $_SERVER['REQUEST_URI'];

// Remove query string from the URI
$requestUri = parse_url($requestUri, PHP_URL_PATH);

// Define the routes and their corresponding handlers
$routes = [
    '/' => [
        'path' => 'views/index.php',
        'headers' => []
    ],
    '/login' => [
        'path' => 'views/login.php',
        'headers' => []
    ],
    '/signup' => [
        'path' => 'views/signup.php',
        'headers' => []
    ],
    '/signup/submit' => [
        'path' => 'controllers/Signup.php',
        'headers' => []
    ],
    '/login/submit' => [
        'path' => 'controllers/Login.php',
        'headers' => []
    ],
    '/api/v1.0/produit/list' => [
        'path' => 'api/v1/lire.php',
        'headers' => []
    ],
    '/api/v1.0/produit/new' => [
        'path' => 'api/v1/creer.php',
        'headers' => []
    ],
    '/api/v1.0/produit/update' => [
        'path' => 'api/v1/modifier.php',
        'headers' => []
    ],
    '/api/v1.0/produit/delete' => [
        'path' => 'api/v1/supprimer.php',
        'headers' => []
    ],
    '/api/v1.0/produit/listone' => [
        'path' => 'api/v1/lire_un.php',
        'headers' => []
    ],
    '/api/v1.0/produit/listmany' => [
        'path' => 'api/v1/lire_des.php',
        'headers' => []
    ],
    // assets
    '/styles/index.css' => [
        'path' => 'styles/index.css',
        'headers' => ["Content-Type: text/css"]
    ],
    '/images/theme-icon.svg' => [
        'path' => 'images/theme-icon.svg',
        'headers' => ["Content-Type: image/svg+xml"]
    ],
    '/js/dist/APIFetch.js' => [
        'path' => 'js/dist/APIFetch.js',
        'headers' => ["Content-Type: application/javascript"]
    ],
    '/js/dist/helpers/dom.js' => [
        'path' => 'js/dist/helpers/dom.js',
        'headers' => ["Content-Type: application/javascript"]
    ],
    '/js/dist/helpers/fetch_functions.js' => [
        'path' => 'js/dist/helpers/fetch_functions.js',
        'headers' => ["Content-Type: application/javascript"]
    ],
    '/js/dist/helpers/theme-toggle.js' => [
        'path' => 'js/dist/helpers/theme-toggle.js',
        'headers' => ["Content-Type: application/javascript"]
    ],
];

// Function to match the requested URI against the defined routes
function matchRoute($requestUri, $routes)
{
    foreach ($routes as $pattern => $handler) {
        // Convert the pattern to a regex pattern
        $regexPattern = preg_replace('/\//', '\/', $pattern);
        if (preg_match("/^$regexPattern$/", $requestUri)) {
            return $handler;
        }
    }
    return null;
}

// Match the requested URI to a handler
$handler = matchRoute($requestUri, $routes);

if ($handler) {
    // Include the corresponding handler file
    if (count($handler['headers']) > 0) {
        foreach ($handler['headers'] as $header) {
            header($header);
        }
    }
    include BASE_DIR . '/' . $handler['path'];
} else {
    // Handle 404 Not Found
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}
// else {
//     // Handle special cases with parameters
//     if (preg_match('/^\/api\/v1\.0\/produit\/delete\/([0-9]+)\/?$/', $requestUri, $matches)) {
//         $_GET['id'] = $matches[1];
//         include BASE_DIR . '/api/v1/supprimer.php';
//     } elseif (preg_match('/^\/api\/v1\.0\/produit\/listone\/([0-9]+)\/?$/', $requestUri, $matches)) {
//         $_GET['id'] = $matches[1];
//         include BASE_DIR . '/api/v1/lire_un.php';
//     } elseif (preg_match('/^\/api\/v1\.0\/produit\/listmany\/((id\[\]=\d+&?)+)\/?$/', $requestUri, $matches)) {
//         parse_str($matches[1], $queryParams);
//         $_GET = array_merge($_GET, $queryParams);
//         include BASE_DIR . '/api/v1/lire_des.php';
//     } else {
//         // Handle 404 Not Found
//         header("HTTP/1.0 404 Not Found");
//         echo "404 Not Found";
//     }
// }
