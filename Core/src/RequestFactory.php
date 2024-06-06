<?php

namespace Core;

use Core\Http\Request;
use Core\Http\PostRequest;
use Core\Http\RequestMethod;

class RequestFactory
{
    public static function MakeRequestFromGlobals(): Request
    {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $headers = getallheaders() ?? [];
        if ($method == 'POST') {
            $body = $_POST;
            return new PostRequest($path, $method, $body, $headers);
        }
        return new Request($path, $method, $headers);
    }
}
