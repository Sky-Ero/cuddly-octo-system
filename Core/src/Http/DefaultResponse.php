<?php

namespace Core\Http;

class DefaultResponse extends Response
{
    public function __construct(
        public string $content,
        public int $status = 200,
        public array $headers = [],
        public array $cookies = []
    ) {
    }

    public function send(): void
    {
        parent::send();
        echo $this->content;
    }
}
