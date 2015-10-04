<?php

namespace DH\ShoppingCart\Controllers;


use DH\Mvc\BaseController;
use DH\Mvc\View;
use DH\ShoppingCart\Forms\AddToCartForm;
use DH\ShoppingCart\Models\CategoriesModel;
use DH\ShoppingCart\models\ProductsModel;
use DH\ShoppingCart\Models\ViewModels\Category\CategoryView;

class CategoriesController extends BaseController
{
    public function view()
    {
        $categoryView = new CategoryView();
        $categoryView->categoryName = urldecode($this->input->get(0));
        $categoriesModel = new CategoriesModel();
        $categoryView->categories = $categoriesModel->getAllCategories();
        $productsModel = new ProductsModel();
        if(!$categoryView->categoryName) {
            $categoryView->products = $productsModel->getAllProductsForSale($this->session->userId);
        } else {
            $categoryView->products = $productsModel->getAllProductsForSaleFromCategory($categoryView->categoryName, $this->session->userId);
        }

        $view = View::getInstance();
        View::title('Categories');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'categories.all');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $categoryView);
    }
}