<?php

use TestApp\UsersService;

class Routes
{
    public function __construct()
    {
    }

    #[Route(path: "/", methods: ["GET"])]
    public function index(UsersService $id, $any_var)
    {
        return "Hello, World!";
    }
}