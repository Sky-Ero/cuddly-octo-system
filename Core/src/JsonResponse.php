<?php

namespace Core;

class JsonResponse
{
    public function __construct(
        public string $content,
        public int $status = 200,
        public array $headers = []
    ) {
    }
}
