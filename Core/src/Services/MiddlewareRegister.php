<?php

namespace Core\Services;

use Core\Services\MiddlewareAbstract;
use Core\Services\MiddlewareTypes;

class MiddlewareRegister implements ServiceInterface
{

    private static $instance;
    private array $middlewares = [];


    private function __construct()
    {
        foreach (MiddlewareTypes::cases() as $type) {
            $this->middlewares[$type->value] = [];
        }
    }

    public static function getInstance(): MiddlewareRegister
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function run()
    {
        // TODO
    }

    public function RegisterMiddleware(MiddlewareAbstract $middleware): void
    {
        $type = $middleware->getMiddlewareType();
        $this->middlewares[$type][] = $middleware;
    }
    public function CallMiddleware(MiddlewareType $type): void
    {
        foreach ($this->middlewares[$type->value] as $middleware) {
            $middleware->run();
        }
    }
}