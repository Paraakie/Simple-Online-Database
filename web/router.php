<?php

use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;

$collection = new RouteCollection();

/**
 * Redirects to log-in or showAccounts when address isn't specified
 */
$collection->attachRoute(
    new Route(
        '/',
        array(
            '_controller' => 'jis\a2\controller\HomeController::indexAction',
            'methods' => 'GET',
            'name' => 'Home'
        )
    )
);

/**
 * When user is logged-in he can see all his accounts
 */
$collection->attachRoute(
    new Route(
        '/account/',
        array(
            '_controller' => 'jis\a2\controller\BankAccountController::showAccounts',
            'methods' => 'GET',
            'name' => 'showAccounts'
        )
    )
);

/**
 * our login router
 */
$collection->attachRoute(
    new Route(
        '/login/',
        array(
            '_controller' => 'jis\a2\controller\UserAccountController::login',
            'methods' => 'GET',
            'name' => 'login'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/login/',
        array(
            '_controller' => 'jis\a2\controller\UserAccountController::login',
            'methods' => 'POST',
            'name' => 'login'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/signUp/',
        array(
            '_controller' => 'jis\a2\controller\UserAccountController::signUp',
            'methods' => 'POST',
            'name' => 'signUp'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/signUp/',
        array(
            '_controller' => 'jis\a2\controller\UserAccountController::signUp',
            'methods' => 'GET',
            'name' => 'signUp'
        )
    )
);

/**
 * Logout router
 */
$collection->attachRoute(
    new Route(
        '/account/logout',
        array(
            '_controller' => 'jis\a2\controller\HomeController::logout',
            'methods' => 'GET',
            'name' => 'logout'
        )
    )
);

/**
 * Search Screen
 */
$collection->attachRoute(
    new Route(
        '/account/search',
        array(
            '_controller' => 'jis\a2\controller\SearchController::search',
            'methods' => 'GET',
            'name' => 'search'
        )
    )
);

/**
 * Browse Screen
 */
$collection->attachRoute(
    new Route(
        '/account/browse',
        array(
            '_controller' => 'jis\a2\controller\BrowseController::browse',
            'methods' => 'GET',
            'name' => 'browse'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
