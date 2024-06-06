<?php

namespace Core\Services;

use Core\Services\MiddlewareAbstract;
use Core\Services\MiddlewareTypes;
use Core\Config;
use Core\Http\Request;
use Core\Services\ServiceInterface;

class MiddlewareRegister implements ServiceInterface
{

    private static $instance;
    private array $middlewares = [];


    private function __construct()
    {
    }

    public static function getInstance(): MiddlewareRegister
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function run(): void
    {
        // TODO
    }

    public function RegisterMiddleware(MiddlewareAbstract $middleware): void
    {
        $type = $middleware->getMiddlewareType();
        $this->middlewares[$type][] = $middleware;
    }
    public function CallMiddleware(MiddlewareTypes $type, Request &$request): void
    {
        foreach ($this->middlewares[$type->name] as $middleware) {
            $middleware->run($request);
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
        $namespace = $middlewares_config['namespace'];
        foreach ($middlewares_config as $type => $middlewares) {
            if ($type == 'namespace' )
                continue;
            if (!isset($this->middlewares[$type])) {
                $this->middlewares[$type] = [];
            }

            foreach ($middlewares as  $middleware) {
                $this->loadMiddlewareClassFile($middleware);
                $middleware_class = explode('\\', $middleware);
                $middleware_class = $middleware_class[count($middleware_class) - 1];
                $middleware_class = explode('.', $middleware_class)[0];

                $this->middlewares[$type][] = new ($namespace.'\\'.$middleware_class)();
            }
        }
    }
}
