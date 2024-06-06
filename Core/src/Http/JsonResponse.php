<?php

namespace Core\Http;

class JsonResponse
{
    public function __construct(
        public string $content,
        public int $status = 200,
        public array $headers = []
    ) {
    }
}
