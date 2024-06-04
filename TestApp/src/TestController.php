<?php

namespace TestApp;

use Core\{Route, Response, AbstractController};

class TestController extends AbstractController
{
    #[Route(path: "/", methods: ["GET", "POST"], name: "test_hello_route_name")]
    function test_hello(): Response
    {
        return new Response("Hello, World!");
    }

    #[Route(path: "list", methods: ["GET", "POST"], name: "test_list")]
    function test_list(): Response
    {
        return new Response("List, World!");
    }

    #[Route(path: "/create/asd", methods: ["GET", "POST"], name: "test_create")]
    function test_create(): Response
    {
        return new Response("Create, World!");
    }
}
