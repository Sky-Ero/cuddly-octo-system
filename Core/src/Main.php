<?php

namespace Core;

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

        // var_dump(get_declared_interfaces());

        $serviceLoader = new ServiceLoader();

        $router = Router::getInstance();

        $router->warmup($config);

        $request = RequestFactory::MakeRequestFromGlobals();

        $controller_description = $router->match($request);

        $controller_class = explode('/', $controller_description['controller']);
        $controller_class = $controller_class[count($controller_class) - 1];
        
        require($controller_description['controller']);

        $controller = new ($controller_description['namespace'] . '\\' . explode('.', $controller_class)[0])();
        $class_method = $controller_description['class_method'];
        $response = $controller->$class_method($request, $serviceLoader);

        var_dump($response);
    }
}
