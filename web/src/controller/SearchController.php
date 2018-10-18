<?php
/**
 * Created by PhpStorm.
 * @author Sven Gerhards
 * Date: 10/10/2018
 * Time: 5:56 PM
 */

namespace jis\a2\controller;

use jis\a2\model\ProductListModel;
use jis\a2\view\View;

class SearchController extends Controller
{
    /**
     * Display the Search page
     */
    public function search()
    {
        $view = new View('search');
        echo $view->render();
    }

    /**
     * Return the matching Items
     */
    public function returnMatches()
    {
        // get the q parameter from URL
        $q = $_GET['search'];
        if($q === null) {
            $q = "";
        }
        $products = (new ProductListModel())->findProductsWithSimilarName($q, 10);
        if(!$products->valid()) {
            echo "no suggestion";
        } else {
            while (true) {
                $product = $products->current();
                $products->next();
                echo $product->getName();
                if($products->valid()) {
                    echo ", ";
                } else {
                    break;
                }
            }
        }
    }
}
