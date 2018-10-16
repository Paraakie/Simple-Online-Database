<?php
/**
 * Created by PhpStorm.
 * @author Sven Gerhards
 * Date: 10/10/2018
 * Time: 5:56 PM
 */

namespace jis\a2\controller;

use jis\a2\view\View;

class SearchController extends Controller
{
    /**
     * Display the Search page
     */
    public function search(){
        $view = new View('search');
        echo $view->render();
    }
}
