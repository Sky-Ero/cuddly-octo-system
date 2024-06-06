<?php

namespace Core\Http;

class DefaultResponse extends Response
{
    public function __construct(
        public string $content,
        public int $status = 200,
        public array $headers = []
    ) {
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        echo $this->content;
    }
}
