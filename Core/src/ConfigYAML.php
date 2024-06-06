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
            $config_name = basename($file, '.yml');
            self::$config[$config_name] = $config;
        }
    }
}
