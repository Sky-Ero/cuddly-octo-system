<?php

namespace Core;

class AbstractController
{
    public function json(mixed $data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        // TODO json encode data
        return new JsonResponse('Json encoded data');
    }

    public function render(string $template, array $context = []): Response
    {
        // TODO render template
        return new Response('Rendered template');
    }
}
