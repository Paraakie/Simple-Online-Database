<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */

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

    /**
     * render the home page
     */
    public function showHome() {
        //user authentication
        $user = UserAccountController::getCurrentUser();
        if($user === null) {
            return;
        }
        $view = new View("userHome");
        $view->addData("userName", $user->getNickName());
        echo $view->render();
    }
}
