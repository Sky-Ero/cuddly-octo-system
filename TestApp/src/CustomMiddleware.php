<?php

namespace TestApp;

use Core\Http\Request;
use Core\Services\MiddlewareAbstract;
use Core\Services\MiddlewareTypes;
use Core\Http\Redirect;

class CustomMiddleware extends MiddlewareAbstract
{
    public function getMiddlewareName(): string
    {
        return 'CustomMiddleware';
    }

    public function getMiddlewareType(): MiddlewareTypes
    {
        return MiddlewareTypes::REQUEST_CREATED;
    }

    public function run(Request &$request): void
    {
        if ($request->path == '/custom') {
            Redirect::to('/');
        }
        if (key_exists('Authorization', $request->headers) && $request->headers['Authorization'] == '123') {
            Redirect::to('/auth');
        }
    }
}
