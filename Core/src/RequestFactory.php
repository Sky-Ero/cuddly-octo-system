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
        if ($method == 'POST') {
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);
            return new PostRequest($path, $method, $data);
        }
        return new Request($path, $method);
    }
}
