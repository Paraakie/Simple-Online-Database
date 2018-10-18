<?php
/**
 * Created by PhpStorm.
 * User: Paraakie
 * Date: 10/10/2018
 * Time: 7:05 PM
 */

namespace jis\a2\controller;


use jis\a2\model\ProductListModel;
use jis\a2\view\View;

class BrowseController
{
    /**
     * Display the Browse page
     */
    public function browse(){
        $products = (new ProductListModel()) -> findAllProducts();
        $view = new View('browse');
        $view->addData("products", $products);
        echo $view->render();
    }

    /**
     *
     */

}