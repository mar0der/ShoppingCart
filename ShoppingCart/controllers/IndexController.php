<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/2/2015
 * Time: 9:20 PM
 */

namespace My\ShoppingCart\Controllers {


    class IndexController
    {
        public function index()
        {
            $viewModel =  new \My\ShoppingCart\Models\ViewModels\Index\Index();
            $viewModel->setBody('Body') ;

            $view = \My\Mvc\Core\View::getInstance();
            $view->appendToLayout('header', 'header');
            $view->appendToLayout('body', 'Index');
            $view->appendToLayout('footer', 'footer');
            $view->display('layouts.default', $viewModel);
        }

    }
}