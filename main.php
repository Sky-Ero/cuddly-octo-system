<?php

use Core\Kernel;
use Core\RequestFactory;

function main(): void
{
    $kernel = new Kernel();

    $request = RequestFactory::MakeRequestFromGlobals();

    $response = $kernel->handleRequest($request);

    var_dump($response);
}
