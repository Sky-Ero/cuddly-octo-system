<?php

namespace TestApp;

use Core\Router\Route;
use Core\AbstractController;
use Core\Http\DefaultResponse;
use Core\Http\Request;
use Core\Http\PostRequest;
use Core\Http\TemplateResponse;
use Twig\Template;

class TestController extends AbstractController
{
    #[Route(path: "/", methods: ["GET", "POST"], name: "test_hello_route_name")]
    function test_hello(): DefaultResponse
    {
        return new DefaultResponse("Hello, World!");
    }

    #[Route(path: "list", methods: ["GET", "POST"], name: "test_list")]
    function test_list(): DefaultResponse
    {
        return new DefaultResponse("List, World!");
    }

    #[Route(path: "/create/asd", methods: ["GET", "POST"], name: "test_create")]
    function test_create(): DefaultResponse
    {
        return new DefaultResponse("Create, World!");
    }

    #[Route(path: "/post", methods: ["POST"], name: "test_post")]
    function test_post(PostRequest $request, \TestApp\UsersService $usersService): DefaultResponse
    {
        $login = $request->payload['username'];
        $password = $request->payload['password'];
        if ($usersService->checkUserLogin($login, $password)) {
            return new DefaultResponse("Login exists, World!", 302, ['Location' => '/success'], ['login' => $login]);
        }
        return new DefaultResponse("Login created, World!");
    }

    #[Route(path: "/redirect", methods: ["GET"], name: "test_redirect")]
    function test_redirect(Request $request): DefaultResponse
    {
        return new DefaultResponse("Redirect, World!", 302, ['Location' => '/']);
    }

    #[Route(path: "/template", methods: ["GET"], name: "test_template")]
    function test_render(Request $request): TemplateResponse
    {
        return new TemplateResponse(
            'test.html.twig',
            ['name' => 'Test']
        );
    }

    #[Route(path: "/login", methods: ["GET"], name: "test_auth")]
    function test_auth(Request $request): TemplateResponse
    {
        return new TemplateResponse("authorization.twig", []);
    }

    #[Route(path: "/broken", methods: ["GET"], name: "test_broken")]
    function test_broken(Request $request): TemplateResponse
    {
        return new TemplateResponse("broken.twig", []);
    }

    #[Route(path: "/success", methods: ["GET"], name: "test_success")]
    function test_success(Request $request): TemplateResponse
    {
        $login = $request->cookies['login'];
        return new TemplateResponse("authorized.twig", ['login' => $login]);
    }

    #[Route(path: "/index", methods: ["GET"], name: "test_index")]
    function test_index(Request $request): TemplateResponse
    {
        return new TemplateResponse("index.twig", []);
    }
}
