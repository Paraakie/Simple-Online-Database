<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */

use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;

$collection = new RouteCollection();

/**
 * Redirects to log-in or Home when address isn't specified
 */
$collection->attachRoute(
    new Route(
        '/',
        array(
            '_controller' => 'jis\a3\controller\HomeController::indexAction',
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
            '_controller' => 'jis\a3\controller\HomeController::showHome',
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
            '_controller' => 'jis\a3\controller\UserAccountController::login',
            'methods' => 'GET',
            'name' => 'login'
        )
    )
);


$collection->attachRoute(
    new Route(
        '/login/',
        array(
            '_controller' => 'jis\a3\controller\UserAccountController::login',
            'methods' => 'POST',
            'name' => 'login'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/signUp/',
        array(
            '_controller' => 'jis\a3\controller\UserAccountController::signUp',
            'methods' => 'POST',
            'name' => 'signUp'
        )
    )
);

/**
 * Sign up router
 */
$collection->attachRoute(
    new Route(
        '/signUp/',
        array(
            '_controller' => 'jis\a3\controller\UserAccountController::signUp',
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
            '_controller' => 'jis\a3\controller\UserAccountController::logout',
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
            '_controller' => 'jis\a3\controller\SearchController::search',
            'methods' => 'GET',
            'name' => 'search'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/search/find',
        array(
            '_controller' => 'jis\a3\controller\SearchController::returnMatches',
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
            '_controller' => 'jis\a3\controller\BrowseController::browse',
            'methods' => 'GET',
            'name' => 'browse'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/browse/filter',
        array(
            '_controller' => 'jis\a3\controller\BrowseController::getFilteredProducts',
            'methods' => 'GET',
            'name' => 'getFilteredProducts'
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
            '_controller' => 'jis\a3\controller\UserAccountController::checkUserExist',
            'methods' => 'GET',
            'name' => 'checkUserExist'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
