<?php

namespace Core\Services;

use Exception;

class ServiceContainer implements ContainerInterface
{
    // @var ServiceInterface[]
    private static array $services = [];
    private static ?ServiceContainer $instance = null;
    
    private function __construct()
    {
    }

    public static function getInstance(): ServiceContainer
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    /**
     * register service
     * @param ServiceInterface $service
     * @return void
     * @throws Exception
     */

    public static function register(ServiceInterface $service): void
    {
        $class = get_class($service);
        if (isset(self::$services[$class])) {
            throw new Exception("Service $class already registered");
        }
        self::$services[$class] = $service;
    }

    /**
     * get service
     * @param string $class
     * @return ServiceInterface
     * @throws Exception
     */

    public function get(string $class): ServiceInterface
    {
        if (!isset(self::$services[$class])) {
            throw new Exception("Service $class not registered");
        }
        return self::$services[$class];
    }

    public function has(string $id): bool
    {
        return isset(self::$services[$id]);
    }
}
