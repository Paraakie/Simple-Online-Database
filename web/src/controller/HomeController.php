<?php

namespace jis\a2\controller;

use jis\a2\view\View;

/**
 * Class HomeController handles redirecting to the right page when the default page is requested
 *
 * @package jis/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author Isaac Clancy, Junyi Chen, Sven Gerhards
 */
class HomeController extends Controller
{
    /**
     * Account Index action
     */
    public function indexAction()
    {
        //session checking
        session_start();
        if (isset($_SESSION['userID'])) {
            //user is logged in, redirect to the home page
            $this->redirect('showHome');
        } else {
            //user hasn't logged in, to login page
            $this->redirect('login');
        }
    }

    public function showHome() {
        $view = new View("userHome");
        echo $view->render();
    }

    /**
     *  Logout Action
     */
    public function logout()
    {
        //Deleting session
        session_start();
        session_destroy();
        $this->redirect('login');
    }
}
