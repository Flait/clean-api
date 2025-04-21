<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList();

        // === AUTH ===
        $router->add(MethodRestrictedRoute::from('auth/register', 'Auth:register', ['POST']));
        $router->add(MethodRestrictedRoute::from('auth/login', 'Auth:login', ['POST']));

        // === USERS (only for admin) ===
        $router->add(MethodRestrictedRoute::from('users', 'User:list', ['GET']));
        $router->add(MethodRestrictedRoute::from('users/<id>', 'User:detail', ['GET']));
        $router->add(MethodRestrictedRoute::from('users', 'User:create', ['POST']));
        $router->add(MethodRestrictedRoute::from('users/<id>', 'User:update', ['PUT']));
        $router->add(MethodRestrictedRoute::from('users/<id>', 'User:delete', ['DELETE']));

        // === ARTICLES ===
        $router->add(MethodRestrictedRoute::from('articles', 'Article:list', ['GET']));
        $router->add(MethodRestrictedRoute::from('articles/<id>', 'Article:detail', ['GET']));
        $router->add(MethodRestrictedRoute::from('articles', 'Article:create', ['POST']));
        $router->add(MethodRestrictedRoute::from('articles/<id>', 'Article:update', ['PUT']));
        $router->add(MethodRestrictedRoute::from('articles/<id>', 'Article:delete', ['DELETE']));

        return $router;
    }
}
