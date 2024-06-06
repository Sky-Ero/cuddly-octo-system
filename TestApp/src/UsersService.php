<?php

namespace TestApp;

use Core\Services\ServiceInterface;

class UsersService implements ServiceInterface
{

    private static $instance;
    public function run(): void
    {
    }

    public static function getInstance(): UsersService
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function getUsers(): array
    {
        return ['user', 'admin'];
    }
    public function checkUserLogin(string $user, string $password): bool
    {
        return in_array($user, $this->getUsers()) && $password == 'password';
    }
    public function checkUserExists(string $user): bool
    {
        return in_array($user, $this->getUsers());
    }
}
