<?php

namespace Core\Http;

enum RequestMethod: string
{
    case GET = "GET";
    case POST = "POST";

    case UNKNOWN = "";
}

class Request
{
    public RequestMethod $method = RequestMethod::GET;
    public string $path = "/";
    public array $headers = [];

    public function __construct($path, $method, $headers = [])
    {
        $this->method = RequestMethod::tryFrom($method) ?? RequestMethod::UNKNOWN;
        $this->path = $path;
        $this->headers = $headers;
    }
}
