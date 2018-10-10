<?php
/**
 * Created by PhpStorm.
 * User: Paraakie
 * Date: 10/10/2018
 * Time: 7:05 PM
 */

namespace jis\a2\controller;


class BrowseController
{
    /**
     * Display the Browse page
     */
    public function browse(){
        $view = new View('browse');
        echo $view->render();
    }
}