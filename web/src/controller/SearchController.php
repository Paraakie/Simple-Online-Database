<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */

namespace jis\a2\controller;

use jis\a2\model\ProductListModel;
use jis\a2\view\View;

/**
 * Class SearchController handles seraching
 * @package jis\a2\controller
 * @author Isaac Clancy, Junyi Chen, Sven Gerhards
 */
class SearchController extends Controller
{
    /**
     * Display the Search page
     */
    public function search()
    {
        $user = UserAccountController::getCurrentUser();
        if ($user === null) {
            return;
        }
        $view = new View('search');
        echo $view->render();
    }

    /**
     * Return the matching Items
     */
    public function returnMatches()
    {
        $user = UserAccountController::getCurrentUser();
        if ($user === null) {
            return;
        }
        // get the q parameter from URL
        $q = $_GET['search'];
        if ($q === null) {
            $q = "";
        }
        $products = (new ProductListModel())->findProductsWithSimilarName($q, 100);
        $tableData = new View('productsTableBody');
        $tableData->addData('products', $products);
        echo $tableData->render();
    }
}
