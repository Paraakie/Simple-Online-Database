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
        '/home/',
        array(
            '_controller' => 'jis\a2\controller\HomeController::showHome',
            'methods' => 'GET',
            'name' => 'showHome'
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
        '/home/logout/',
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
        '/search/',
        array(
            '_controller' => 'jis\a2\controller\SearchController::search',
            'methods' => 'GET',
            'name' => 'search'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/search/find',
        array(
            '_controller' => 'jis\a2\controller\SearchController::returnMatches',
            'methods' => 'GET',
            'name' => 'returnMatches'
        )
    )
);

/**
 * Browse Screen
 */
$collection->attachRoute(
    new Route(
        '/browse/',
        array(
            '_controller' => 'jis\a2\controller\BrowseController::browse',
            'methods' => 'GET',
            'name' => 'browse'
        )
    )
);

/**
 * Ajax check for user name existence in signup page
 */
$collection->attachRoute(
    new Route(
        '/signUp/checkUserName/:id',
        array(
            '_controller' => 'jis\a2\controller\UserAccountController::checkUserExist',
            'methods' => 'GET',
            'name' => 'checkUserExist'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
