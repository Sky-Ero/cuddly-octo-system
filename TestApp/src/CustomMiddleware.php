<?php

namespace TestApp;

use Core\Http\Request;
use Core\Services\MiddlewareAbstract;
use Core\Services\MiddlewareTypes;

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
        $request->path = 'list';
    }
}
