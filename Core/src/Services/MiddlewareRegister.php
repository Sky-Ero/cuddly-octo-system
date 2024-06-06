<?php

namespace Core\Services;

use Core\Services\MiddlewareAbstract;
use Core\Services\MiddlewareTypes;
use Core\Config;
use Core\Services\ServiceInterface;

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

    private function loadMiddlewareClassFile(string $middleware_file_class)
    {
        if (file_exists($middleware_file_class)) {
            require_once $middleware_file_class;
        }
    }
    public function load(Config $config): void
    {

        $middlewares_config = $config::$config['middlewares'];

        foreach ($middlewares_config as $type => $middlewares) {
            if (!isset($this->middlewares[$type])) {
                $this->middlewares[$type] = [];
            }

            foreach ($middlewares as $middleware) {
                $this->loadMiddlewareClassFile($middleware);
                $middleware_class = explode('\\', $middleware);
                $middleware_class = $middleware_class[count($middleware_class) - 1];
                $middleware_class = explode('.', $middleware_class)[0];

                $this->middlewares[$type][] = new $middleware_class();
            }
        }
    }
}
