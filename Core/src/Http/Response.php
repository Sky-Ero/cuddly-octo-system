<?php

namespace Core\Http;

abstract class Response
{
    public function __construct(
        public string $content,
        public int $status = 200,
        public array $headers = []
    ) {
    }

    public abstract function send(): void;
}
