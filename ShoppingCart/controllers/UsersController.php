<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/2/2015
 * Time: 7:21 PM
 */

namespace My\ShoppingCart\Controllers{


    class UsersController
    {
        public function index()
        {
            echo "Index";
        }

        public function login()
        {
            echo "login";
        }

        public function register()
        {
            if($this->session->userId != null) {
                $this->redirect('/profile');
            }

            $userViewModel = new RegisterUser();
            if ($this->input->post('submit')) {
                $username = $this->input->post('username', 'trim');
                $email = $this->input->post('email', 'trim');
                $pass = $this->input->post('pass', 'trim');
                $passAgain = $this->input->post('passAgain', 'trim');

                $userModel = new UserModel();
                $userViewModel->errors = $userModel->register($username, $email, $pass, $passAgain);

                if (!count($userViewModel->errors)) {
                    $userViewModel->success = true;
                }
            }

            $view = View::getInstance();
            View::title('Register');
            $view->appendToLayout('header', 'header');
            $view->appendToLayout('body', 'user.register');
            $view->appendToLayout('footer', 'footer');
            $view->display('layouts.default', $userViewModel);
        }

    }
}