<?php


class Routes
{
    public function __construct()
    {
    }

    #[Route(path: "/", methods: ["GET"])]
    public function index()
    {
        return "Hello, World!";
    }
}