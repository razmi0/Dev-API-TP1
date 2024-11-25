<?php

namespace API\Controllers;

use Dotenv\Dotenv;
use HTTP\Request;
use HTTP\Response;
use Middleware\Middleware;
use Middleware\Validators\Validator;

const PROJECT_ROOT = __DIR__ . '/../';

require_once PROJECT_ROOT . 'vendor/autoload.php';

// Load the environment variables
$dotenv = Dotenv::createImmutable(PROJECT_ROOT, '.env.local');
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
