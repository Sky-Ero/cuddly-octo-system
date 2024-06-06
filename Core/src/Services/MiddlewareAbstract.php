<?php

namespace Core\Services;

use Core\Http\Request;
use Core\Services\MiddlewareTypes;


abstract class MiddlewareAbstract
{
    abstract public function getMiddlewareName(): string;

    abstract public function getMiddlewareType(): MiddlewareTypes;

    abstract public function run(Request &$request): void;
}