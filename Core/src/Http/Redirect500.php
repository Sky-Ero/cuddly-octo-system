<?php

namespace Core\Http;

use Core\Http\Response;
use Core\Http\Redirect;

class Redirect500 extends Response
{
    private array $context;
    public function __construct(array $context = [])
    {
        parent::__construct("", 500);

        $this->context = $context;
    }

    public function send(): void
    {
        $response = new TemplateResponse(
            '@default/error500.twig',
            $this->context
        );
        $response->send();
        parent::send();
        die();
    }
}