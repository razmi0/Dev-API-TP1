<?php



namespace API\Attributes;

// use API\Endpoints;
use Attribute;
use ReflectionClass;


#[Attribute(Attribute::TARGET_CLASS)]
class Route
{
    public function __construct(
        public array $methods,
        public string $path
    ) {}

    public static function getRoutes(string $namespace): array
    {
        $routes = [];
        $classes = get_declared_classes();

        return $classes;

        // foreach ($classes as $class) {
        //     if (str_starts_with($class, $namespace)) {
        //         $reflection = new ReflectionClass($class);
        //         $attributes = $reflection->getAttributes(self::class);

        //         if (count($attributes) > 0) {
        //             $route = $attributes[0]->newInstance();
        //             $routes[] = [
        //                 "methods" => $route->methods,
        //                 "path" => $route->path,
        //                 "class" => $class
        //             ];
        //         }
        //     }
        // }

        // return $routes;
    }


    public static function matchRoute(string $requestUri, array $routes): ?array
    {
        foreach ($routes as $route) {
            $regexPattern = preg_replace('/\//', '\/', $route['path']);
            if (preg_match("/^$regexPattern$/", $requestUri)) {
                return $route;
            }
        }
        return null;
    }

    public static function handleRoute(array $route): void
    {
        $class = $route['class'];
        $instance = new $class();
        $instance->handle();
    }

    public static function run(string $requestUri, array $routes): void
    {
        $route = self::matchRoute($requestUri, $routes);

        if ($route) {
            self::handleRoute($route);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }

    public static function runRoutes(string $requestUri, string $namespace): void
    {
        $routes = self::getRoutes($namespace);
        self::run($requestUri, $routes);
    }
}


$routes = get_declared_classes();
print_r($routes);
