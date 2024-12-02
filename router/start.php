<?php


use API\Routing\Route;

require_once BASE_DIR . '/vendor/autoload.php';

$namespace = 'API\\Endpoints';
$directory = BASE_DIR . '/api/v1';



$classesWithRoute = [];

// Iterate through each file in the directory
$file_iterator = new DirectoryIterator($directory);

foreach ($file_iterator as $file) {
    if ($file->isDot() || $file->getExtension() !== 'php') {
        continue;
    }



    // Get the full class name
    $className = $namespace . '\\' . $file->getBasename('.php');


    try {
        // Use reflection to check for Route attribute
        $reflectionClass = new ReflectionClass($className);
    } catch (\Throwable $th) {
        throw $th;
    }

    $attributes = $reflectionClass->getAttributes(Route::class);
    var_dump($attributes);
    die();

    if (!empty($attributes)) {
        $classesWithRoute[] = $className;
    }
}

// Output the classes with Route attribute
