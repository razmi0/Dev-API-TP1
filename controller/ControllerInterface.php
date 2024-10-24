<?php

namespace Controller;

use HTTP\Request;
use HTTP\Response;
use Model\Schema\Schema;
use HTTP\Error;

interface ControllerInterface
{
    public function __construct(Request $request, Response $response, Schema $schema, callable $handler);
}
