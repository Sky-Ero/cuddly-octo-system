<?php

namespace Core\Http;


class PostRequest extends Request
{
    public array $payload;
    public function __construct(string $path, string $method, array $payload, array $headers)
    {
        parent::__construct($path, $method, $headers);
        $this->method = RequestMethod::tryFrom($method) ?? RequestMethod::UNKNOWN;
        $this->payload = $payload;
    }
}