<?php

namespace Core;

class RequestFactory
{
    public static function MakeRequestFromGlobals(): Request
    {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        return new Request($path, $method);
    }
}
