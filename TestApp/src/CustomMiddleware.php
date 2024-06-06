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

    public function getRequestedServices(): array
    {
        return [];
    }

    public function run(Request &$request, ...$services): void
    {
        if ($request->path == '/custom') {
            Redirect::to('/');
        }
    }
}
