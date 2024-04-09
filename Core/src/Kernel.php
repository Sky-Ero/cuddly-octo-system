<?php

namespace Core;

class Kernel
{

    private Router $router;

    public function __construct()
    {
        $this->router = Router::getInstance();
    }

    public function handleRequest(Request $request): Response
    {
        [$routeObject, $routeMethod] = $this->router->match($request);

        if ($routeObject === null) {
            return new Response("Not found", 404);
        }

        $response = $routeObject->$routeMethod($request);

        return $response;
    }
}
