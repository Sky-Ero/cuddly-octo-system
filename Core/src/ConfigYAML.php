<?php

namespace Core;

use Core\Config;

class ConfigYAML extends Config
{
    public static array $config = [];

    public static function load(string $file): void
    {
        if (file_exists($file)) {
            $config = yaml_parse_file($file);
            self::$config = array_merge(self::$config, $config);
        }
    }
}
