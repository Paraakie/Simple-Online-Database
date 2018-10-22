<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */

namespace jis\a2\controller;


use jis\a2\model\CategoryListModel;
use jis\a2\model\ProductListModel;
use jis\a2\view\View;

class BrowseController
{
    /**
     * Display the Browse page
     */
    public function browse()
    {
        $user = UserAccountController::getCurrentUser();
        if($user === null) {
            return;
        }
        $products = (new ProductListModel())->findAllProducts();
        $categories = (new CategoryListModel())->findAllCategories();
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
        $user = UserAccountController::getCurrentUser();
        if($user === null) {
            return;
        }
        $stock = $_GET['stock'];
        $categories = $_GET['categories'];

        $productList = new ProductListModel();
        $products = null;
        if($stock === 'inStock') {
            if($categories === null) {
                $products = $productList->findProductsInStock();
            } else {
                $products = $productList->findInStockProductsWithCategory($categories);
            }
        } elseif ($stock === 'outStock') {
            if($categories === null) {
                $products = $productList->findProductsNotInStock();
            } else {
                $products = $productList->findNotInStockProductsWithCategory($categories);
            }
        } else {
            if($categories === null) {
                $products = $productList->findAllProducts();
            } else {
                $products = $productList->findProductsWithCategory($categories);
            }
        }
        $tableData = new View('productsTableBody');
        $tableData->addData('products', $products);
        echo $tableData->render();
    }
}