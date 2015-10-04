<?php

namespace DH\ShoppingCart\Controllers;


use DH\Mvc\BaseController;
use DH\Mvc\View;
use DH\ShoppingCart\Models\BindingModels\Cart\AddProduct;
use DH\ShoppingCart\Models\CartModel;
use DH\ShoppingCart\Models\UserModel;
use DH\ShoppingCart\Models\ViewModels\Cart\CartView;

class CartController extends BaseController
{
    public function add(AddProduct $model = null)
    {
        if (!$this->session->userId) {
            $this->redirect('/');
            exit;
        }

        if ($model != null && $model->modelState == true) {
            $cartModel = new CartModel();
            $cartModel->addProduct($model, $this->session->userId);
        }

        $this->redirect('/cart/view');
    }

    public function view()
    {
        if(!$this->session->userId) {
            $this->redirect('/');
            exit;
        }

        $cartModel = new CartModel();
        $products = $cartModel->getProducts($this->session->userId);
        $cartView = new CartView();
        $cartView->products = $products;

        $view = View::getInstance();
        View::title('Cart');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'cart.cart');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $cartView);
    }

    public function remove()
    {
        if(!$this->session->userId){
            $this->redirect('/');
            exit;
        }

        $cartModel = new CartModel();
        $cartModel->removeProduct($this->input->get(0), $this->session->userId);
        $this->redirect('/cart/view');
    }

    public function checkout()
    {
        if(!$this->session->userId) {
            $this->redirect('/');
            exit;
        }

        $userModel = new UserModel();
        $userInfo = $userModel->getUserInfo($this->session->userId);
        $cartModel = new CartModel();
        $errors = $cartModel->checkOut($userInfo);

        if(count($errors)) {
            print_r($errors);
        } else {
            $this->redirect('/cart/view');
        }
    }
}