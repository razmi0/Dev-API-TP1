<?php



namespace API\Routing;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Route
{
    public function __construct(
        public array $methods,
        public string $path
    ) {}
}
