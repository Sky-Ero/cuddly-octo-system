<?php

namespace Core;
use Core\Services\ContainerInterface;

/**
 * 
 * TODO cache
 * TODO add support for yaml
 * TODO add support for schema
 * TODO add support for schema validation
 */
abstract class Config implements ContainerInterface
{
    public static array $config = [];

    /**
     * load config file
     * @param string $file
     * @return void
     * @throws \Exception
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     */

    public static function load(string $file): void
    {
        if (file_exists($file)) {
            $config = include $file;
            self::$config = array_merge(self::$config, $config);
        }
    }

    /**
     * get config value
     * @param string $key
     * @return mixed
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function get(string $key): mixed
    {
        return self::$config.$key;
    }

    /**
     * set config value
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function set(string $key, mixed $value): void
    {
        self::$config[$key] = $value;
    }

    /**
     * check if config key exists
     * @param string $key
     * @return bool
     * @throws \Exception
     * @throws \InvalidArgumentException
     */

    public function has(string $key): bool
    {
        return array_key_exists($key, self::$config);
    }
}
