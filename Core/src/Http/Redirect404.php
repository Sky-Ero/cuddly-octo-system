<?php

namespace Core\Http;

use Core\Http\Response;
use Core\Http\Redirect;

class Redirect404 extends Response
{
    public function __construct()
    {
        parent::__construct("", 404);
    }
    public function send(): void
    {
        $response = new TemplateResponse(
            '@default/error400.twig',
            [
                'errno' => 404,
                'errstr' => 'Controller not found',
            ]
        );
        $response->send();
        parent::send();
        die();
    }
}