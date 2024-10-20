<?php

namespace Controller;

use DTO\Request;
use DTO\Response;
use Model\Schema\Schema;

interface ControllerInterface
{
    public function __construct(array $methods, Request $request, Response $response, Schema $schema);
    public function handleRequest(callable $anonymous_handler): void;
}
