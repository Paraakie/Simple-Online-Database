<?php

namespace jis\a2\controller;


use jis\a2\model\CategoryListModel;
use jis\a2\model\ProductListModel;
use jis\a2\view\View;

/**
 * Class BrowseController Handles all requests related to browse product page
 * @package jis\a3\controller
 * @author Issac Clancy, Junyi Chen, Sven Gerhards
 */
class BrowseController
{
    /**
     * Display the Browse page
     */
    public function browse()
    {
        //user authentication
        $user = UserAccountController::getCurrentUser();
        if ($user === null) {
            return;
        }
        //finds all products in our database
        $products = (new ProductListModel())->findAllProducts();
        //finds all categories in our database
        $categories = (new CategoryListModel())->findAllCategories();
        //render the browse page
        $view = new View('browse');
        $view->addData("products", $products);
        $view->addData('categories', $categories);
        echo $view->render();
    }

    /**
     * Gets the products that match the filter as html table rows.
     */
    public function getFilteredProducts()
    {
        //user authentication
        $user = UserAccountController::getCurrentUser();
        if ($user === null) {
            return;
        }
        //check filter bar
        $stock = $_GET['stock'];
        $categories = $_GET['categories'];

        $productList = new ProductListModel();
        $products = null;
        //display correct products according to filter bar seletction
        if ($stock === 'inStock') {
            if ($categories === null) {
                $products = $productList->findProductsInStock();
            } else {
                $products = $productList->findInStockProductsWithCategory($categories);
            }
        } elseif ($stock === 'outStock') {
            if ($categories === null) {
                $products = $productList->findProductsNotInStock();
            } else {
                $products = $productList->findNotInStockProductsWithCategory($categories);
            }
        } else {
            if ($categories === null) {
                $products = $productList->findAllProducts();
            } else {
                $products = $productList->findProductsWithCategory($categories);
            }
        }
        //adds data to table
        $tableData = new View('productsTableBody');
        $tableData->addData('products', $products);
        echo $tableData->render();
    }
}
