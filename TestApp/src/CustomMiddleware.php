<?php

namespace TestApp;

use Core\Http\Request;
use Core\Services\MiddlewareAbstract;
use Core\Services\MiddlewareTypes;

class CustomMiddleware implements MiddlewareAbstract
{
    public function getMiddlewareType(): MiddlewareTypes
    {
        return MiddlewareTypes::CONTROLLER_MATCHED;
    }

    public function run(Request &$request): void
    {
        $request->path = 'list';
    }
}
