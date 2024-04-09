<?php

namespace Core;

use Attribute;


#[Attribute]
class Route
{

    public function __construct(
        public string $path,
        public string $function
    ) {
    }
}
