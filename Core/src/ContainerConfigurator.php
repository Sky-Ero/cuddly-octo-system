<?php

namespace Core;

use Core\ServiceContainer;
use Core\Router;

class ContainerConfigurator
{
    public static function configure(ServiceContainer $container): void
    {
        $container->register(new Router());
    }
}