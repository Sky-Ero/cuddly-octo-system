<?php

namespace Core;

use Core\Router\Router;
use Core\Services\ServiceContainer;
use Core\ServiceLoader;
use Core\ConfigYAML;
use Core\Http\DefaultResponse;
use Core\Http\Redirect;
use Core\Http\TemplateResponse;
use Core\RequestFactory;
use Core\Services\MiddlewareRegister;
use Core\Services\MiddlewareTypes;
use Core\Http\Redirect404;
use Core\Http\Redirect500;
use Core\Http\Response;

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

    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $response = new Redirect500([
            'errno' => $errno,
            'errstr' => $errstr,
            'errfile' => $errfile,
            'errline' => $errline,
        ]);

        $response->send();
    }

    public static function run()
    {
        try {
            set_error_handler([__CLASS__, 'errorHandler']);

            $config = new ConfigYAML();
            $config->load(__DIR__ . '/../../config/routes.yml');
            $config->load(__DIR__ . '/../../config/middlewares.yml');
            $config->load(__DIR__ . '/../../config/services.yml');

            $service_loader = new ServiceLoader();
            $service_loader->load($config);
            $service_container = ServiceContainer::getInstance();
            $service_loader->register($service_container);

            $middleware_register = ($service_container->get(MiddlewareRegister::class));

            $middleware_register->load($config);

            $router = Router::getInstance();

            $router->warmup($config);

            $request = RequestFactory::MakeRequestFromGlobals();
            $middleware_register->CallMiddleware(MiddlewareTypes::REQUEST_CREATED, $request);

            $controller_description = $router->match($request);

            if (!$controller_description) {
                $response = new Redirect404();
                $response->send();
            }
            $controller_class = explode('/', $controller_description['controller']);
            $controller_class = $controller_class[count($controller_class) - 1];
            $controller_dependencies = $controller_description['dependencies'];
            $controller_services = [];

            foreach ($controller_dependencies as $dependency) {
                if (!$service_container->has($dependency)) {
                    continue;
                }
                $controller_services[] = $service_container->get($dependency);
            }

            $controller = new ($controller_description['namespace'] . '\\' . explode('.', $controller_class)[0])();
            $class_method = $controller_description['class_method'];
            $response = $controller->$class_method($request, ...$controller_services);
            $response->send();
        } catch (\Exception $e) {
            self::errorHandler(0, $e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
