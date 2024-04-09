<?php

namespace TestApp;

use Core\{Route, Response, AbstractController};

class TestController extends AbstractController
{
    #[Route(path: "/", methods: ["GET"])]
    function test_hello(): Response
    {
        return new Response("Hello, World!");
    }
}
