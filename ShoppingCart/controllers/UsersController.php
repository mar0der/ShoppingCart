<?php

namespace DH\ShoppingCart\Controllers;

use DH\Mvc\BaseController;
use DH\Mvc\View;
use DH\ShoppingCart\models\ProductsModel;
use DH\ShoppingCart\Models\UserModel;
use DH\ShoppingCart\Models\ViewModels\User\MyProducts;
use DH\ShoppingCart\Models\ViewModels\User\ProfileUser;
use DH\ShoppingCart\Models\ViewModels\User\RegisterUser;

class UsersController extends BaseController
{
    /**
     * [Route("register")]
     */
    public function register(\DH\ShoppingCart\Models\BindingModels\User\RegisterUser $model = null)
    {
        if ($this->session->userId != null) {
            $this->redirect('/profile');
        }
        View::registerForm(\DH\ShoppingCart\Forms\RegisterForm::create($model->username, $model->email));
        $userViewModel = new RegisterUser();
        if ($model) {
            if ($model->modelState == true) {
                View::registerForm(\DH\ShoppingCart\Forms\RegisterForm::create());
                $userModel = new UserModel();
                $userViewModel->errors = $userModel->register($model);

                if (!count($userViewModel->errors)) {
                    $userViewModel->success = true;
                }
            } else {
                $userViewModel->errors = $model->errors;
            }
        }

        $view = View::getInstance();
        View::title('Register');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.register');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $userViewModel);
    }

    /**
     * [Route("login")]
     */
    public function login(\DH\ShoppingCart\Models\BindingModels\User\LoginUser $model = null)
    {
        if ($this->session->userId != null) {
            $this->redirect('/profile');
        }

        $viewModel = new \DH\ShoppingCart\Models\ViewModels\User\LoginUser();
        if ($model) {
            if ($model->modelState) {
                $username = $model->username;
                $password = $model->password;

                $userModel = new UserModel();
                $result = $userModel->login($username, $password);

                if (!$result) {
                    $viewModel->errors[] = 'Invalid password.';
                } else {
                    $this->session->userId = $result['id'];
                    $this->session->user = $result;
                    $this->redirect('/profile');
                }
            } else {
                $viewModel->errors = $model->errors;
            }
        }

        $view = View::getInstance();
        View::title('Login');
        View::loginForm(\DH\ShoppingCart\Forms\LoginForm::create($model->username));
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.login');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);
    }

    /**
     * [Route("profile")]
     */
    public function profile()
    {
        if ($this->session->userId == null) {
            $this->redirect('/login');
        }


        $userModel = new UserModel();
        $userInfo = $userModel->getUserInfo($this->session->userId);
        $viewModel = new ProfileUser();
        $viewModel->username = $userInfo['username'];
        $viewModel->money = $userInfo['money'];
        $viewModel->email = $userInfo['email'];
        $viewModel->role = $userInfo['role'];

        $view = View::getInstance();
        View::title('Profile');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.profile');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);
    }

    public function myProducts()
    {
        if(!$this->session->userId){
            $this->redirect('/');
            exit;
        }

        $productsModel = new ProductsModel();
        $myProducts = $productsModel->getUserProducts($this->session->userId);

        $productView = new MyProducts();
        $productView->products = $myProducts;

        $view = View::getInstance();
        View::title('My Products');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.myProducts');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $productView);
    }

    /**
     * [Route("logout")]
     */
    public function logout()
    {
        $this->session->destroySession();
        $this->redirect('/login');
    }
}