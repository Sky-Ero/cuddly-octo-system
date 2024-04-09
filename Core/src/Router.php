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
        $classLoader = require __DIR__ . '/../../vendor/autoload.php';

        // get all classes extended by AbstractController and find method with route attribute
        $classes = $classLoader->getClassMap();
        foreach ($classes as $class => $path) {
            $reflection = new ReflectionClass($class);
            if ($reflection->isSubclassOf(AbstractController::class)) {
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method) {
                    $attributes = $method->getAttributes(Route::class);
                    if (count($attributes) > 0) {
                        $route = $attributes[0]->newInstance();
                        $this->routes[$route->path] = $class . '::' . $method->getName();
                    }
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

    public function match(Request $request): array | null
    {
        $routeControllerName = $this->routes[$request->path] ?? '';
        if ($routeControllerName === '') {
            return null;
        }

        $routeClass = explode('::', $routeControllerName)[0];
        $object = new $routeClass();
        $routeMethod = explode('::', $routeControllerName)[1];
        return [$object, $routeMethod];
    }
}
