<?php

namespace Core\Http;

abstract class Response
{
    public function __construct(
        public string $content,
        public int $status = 200,
        public array $headers = [],
        public array $cookies = []
    ) {
    }

    public function send(): void{

        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        foreach ($this->cookies as $key => $value) {
            setcookie($key, $value);
        }
    }
}
