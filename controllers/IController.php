<?php

namespace API\Controllers;

use Dotenv\Dotenv;
use HTTP\Request;
use HTTP\Response;
use Middleware\Middleware;
use Middleware\Validators\Validator;


require_once BASE_DIR . '/vendor/autoload.php';

// Load the environment variables
$dotenv = Dotenv::createImmutable(BASE_DIR, '.env.local');
$dotenv->load();

/**
 * Interface IController
 * 
 * This interface is the base for all the controllers.
 */
interface IController
{
    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator);

    /**
     * This method is called by the router to handle the request
     */
    public function handle(): void;
}
