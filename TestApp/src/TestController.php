<?php

namespace TestApp;

use Core\Router\Route;
use Core\AbstractController;
use Core\Http\Response;
use Core\Http\Request;
use Core\Http\PostRequest;

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

    #[Route(path: "/post", methods: ["POST"], name: "test_post")]
    function test_post(PostRequest $request): Response
    {
        return new Response(json_encode($request->payload));
    }
}
