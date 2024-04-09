<?php

namespace Core;

class Response
{
    public function __construct(
        public string $content,
        public int $status = 200,
        public array $headers = []
    ) {
    }
}
