<?php

namespace DH\ShoppingCart\Controllers\Admin;

use DH\Mvc\BaseController;
use  DH\Mvc\View;
use DH\ShoppingCart\models\BindingModels\admin\products\ViewProducts;
use DH\ShoppingCart\models\ProductsModel;
use DH\ShoppingCart\Models\ViewModels\Admin\Products\AddToMarket;

class ProductsController extends BaseController
{
    public function index()
    {
        if(!$this->isAdmin()) {
            $this->redirect('/profile');
        }
        $this->redirect('/admin/products/view');
    }

    public function add( \DH\ShoppingCart\models\BindingModels\Admin\Products\AddProduct $postData = null)
    {
        if(!$this->isAdmin()) {
            $this->redirect('/profile');
        }

        $categoriesModel = new \DH\ShoppingCart\Models\CategoriesModel();
        $allCategories = $categoriesModel->getAllCategories();
        if($postData){
            if($postData->modelState){
                $productModel = new ProductsModel();
                $productModel->addProduct($postData);
            }else{
                var_dump($postData);
                echo "reload the form";
                die;
            }

            $this->redirect('/admin/products/view');
        }
        $view = View::getInstance();
        View::title('..::Admin::.. Add Product ');
        View::addProductsForm(\DH\ShoppingCart\Forms\Admin\Products\AddProduct::create($allCategories));
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'admin.products.add');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default');
    }

    public function view()
    {
        if(!$this->isAdmin()) {
            $this->redirect('/profile');
        }

        $productsModel = new ProductsModel();
        $allProducts = $productsModel->getAllProducts();
        $viewModel = new \DH\ShoppingCart\Models\ViewModels\Admin\Products\ViewProducts();
        $viewModel->products = $allProducts;
        $view = View::getInstance();
        View::title('..::Admin::.. View Products ');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'admin.products.view');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);
    }

    public function addToMarket(\DH\ShoppingCart\Models\BindingModels\Admin\Products\AddToMarket $postData = null)
    {
        if(!$this->isAdmin()) {
            $this->redirect('/profile');
        }

        if($postData){

            if($postData->modelState){

                $productModel = new ProductsModel();
                $productModel->addToMarket($postData, $this->session->userId);
                $this->redirect('/admin/products/view');
                die;
            }else{
                var_dump($postData);
                echo "reload the form";
                die;
            }

            $this->redirect('/admin/products/view');
        }

        $productId = $this->input->get(0);
        $productsModel = new ProductsModel();
        $product = $productsModel->getProductById($productId);
        $addToMarketViewModel = new AddToMarket();
        $addToMarketViewModel->product = $product;
        $view = View::getInstance();
        View::title('..::Admin::.. Add Products To Market');
        View::AddProductToMarketForm(\DH\ShoppingCart\Forms\Admin\Products\AddToMarket::create($productId));
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'admin.products.addtomarket');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $addToMarketViewModel);
    }
}