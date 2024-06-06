<?php

namespace Core;

use Core\Router\Router;
use Core\Services\ServiceContainer;
use Core\ServiceLoader;
use Core\ConfigYAML;
use Core\RequestFactory;
use Core\Services\MiddlewareRegister;
use Core\Services\MiddlewareTypes;

require __DIR__ . '/../vendor/autoload.php';

/**
 * this class is used to load framework
 * 1. load config
 * 2. load services
 * 2.1 router
 * 3. create request
 * 4. find controller
 * 5. call method with request and services
 * 6. send response
 */

class Main
{
    public static function run()
    {
        $config = new ConfigYAML();
        $config->load(__DIR__ . '/../../config/routes.yml');
        $config->load(__DIR__ . '/../../config/middlewares.yml');

        $service_loader = new ServiceLoader();
        $service_loader->loadDirectory(__DIR__);
        $service_container = new ServiceContainer();
        $service_loader->register($service_container);

        $middleware_register = ($service_container->get(MiddlewareRegister::class));

        $middleware_register->load($config);

        $router = Router::getInstance();

        $router->warmup($config);

        $request = RequestFactory::MakeRequestFromGlobals();
        $middleware_register->CallMiddleware(MiddlewareTypes::REQUEST_CREATED, $request);

        $controller_description = $router->match($request);
        

        $controller_class = explode('/', $controller_description['controller']);
        $controller_class = $controller_class[count($controller_class) - 1];

        $controller = new ($controller_description['namespace'] . '\\' . explode('.', $controller_class)[0])();
        $class_method = $controller_description['class_method'];
        $response = $controller->$class_method($request, $service_loader);

        var_dump($response);
    }
}
