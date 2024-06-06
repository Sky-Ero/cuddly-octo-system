<?php

namespace TestApp;

use Core\Http\Request;
use Core\Http\Redirect;
use Core\Http\Response;
use Core\Services\MiddlewareAbstract;
use Core\Services\MiddlewareTypes;
use Core\Config;

class AuthMiddleware extends MiddlewareAbstract
{
    private $users = ['user', 'admin'];

    public function getMiddlewareName(): string
    {
        return 'AuthMiddleware';
    }

    public function getRequestedServices(): array
    {
        return [UsersService::class];
    }

    public function getMiddlewareType(): MiddlewareTypes
    {
        return MiddlewareTypes::REQUEST_CREATED;
    }

    public function run(Request &$request, ...$services): void
    {
        $usersService = $services[0];

        if (
            $request->path == '/success'
            && (!key_exists('login', $request->cookies)
                || empty($request->cookies['login']
                    || !$usersService->checkUserExists($request->cookies['login'])))
        ) { {
                Redirect::to('/login');
            }
        }
    }
}
