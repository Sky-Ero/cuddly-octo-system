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
        $route = $this->router->match($request);

        if ($route === null || $route === '') {
            return new Response("Not found", 404);
        }

        $response = call_user_func($route->function, $request); // TODO DI

        return $response;
    }
}
