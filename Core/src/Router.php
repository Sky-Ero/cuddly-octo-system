<?php

namespace Core;

use ReflectionClass, ReflectionMethod;
use Core\Route;
use Core\AbstractController;

class Router
{
    // @var array<string, string>
    private $routes = array();
    private static $instance;
    private const attributeName = 'Route';

    private function __construct()
    {
        // get all classes extended by AbstractController and find method with route attribute
        $reflectionClass = new ReflectionClass(AbstractController::class);
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach (get_declared_classes() as $class) {
            if (!is_subclass_of($class, AbstractController::class)) {
                continue;
            }

            foreach ($methods as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $this->routes[$route->path] = $reflectionClass->getName() . '::' . $method->getName();
                    echo "Route: " . $route->path . " => " . $reflectionClass->getName() . '::' . $method->getName() . PHP_EOL;
                }
            }
        }
    }

    public static function getInstance(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function match(Request $request): Route
    {
        $routeControllerName = $this->routes[$request->path] ?? '';

        $route = new Route($request->path, $routeControllerName);

        return $route;
    }
}
