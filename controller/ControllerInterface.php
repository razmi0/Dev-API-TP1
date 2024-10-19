<?php

namespace Controller;

use DTO\Request;
use DTO\Response;

interface ControllerInterface
{
    public function __construct(Request $request, Response $response);
    public function handleRequest(callable $anonymous_handler): void;
}
