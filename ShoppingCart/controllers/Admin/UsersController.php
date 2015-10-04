<?php

namespace DH\ShoppingCart\Controllers\Admin;


use DH\Mvc\BaseController;
use DH\ShoppingCart\Models\UserModel;

class UsersController extends BaseController
{
    public function ban()
    {
        if(!$this->isAdmin()){
            $this->redirect('/profile');
        }

        $userId = $this->input->get(0, "int");
        $usersModel = new UserModel();
        $usersModel->banUser($userId, $this->session->user['id']);
        $this->redirect('/profile');
    }
}