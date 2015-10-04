<?php

namespace DH\ShoppingCart\Models;

use DH\Mvc\Common;
use DH\Mvc\DB\SimpleDB;
use DH\Mvc\Validation;

class UserModel extends SimpleDB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register(\DH\ShoppingCart\Models\BindingModels\User\RegisterUser $model)
    {
        $errors = [];
        $username = Common::normalize($model->username, 'trim');
        $email = Common::normalize($model->email, 'trim');
        $password = Common::normalize($model->password, 'trim');
        $validation = new Validation();
        $validation
            ->setRule('minLength', $username, 2, 'Short username')
            ->setRule('email', $email, null, 'Invalid email')
            ->setRule('minLength', $password, 4, 'Short password');

        $validation->validate();
        $errors = $validation->getErrors();

        if(!count($errors)) {
            $checkUser = $this->prepare('SELECT username, email FROM users WHERE username = ? OR email = ?')
                                ->execute(array($username, $email))
                                ->fetchRowAssoc();
            if($checkUser) {
                if($checkUser['username'] == $username) {
                    $errors[] = 'Username already exists.';
                }
                if($checkUser['email'] == $email) {
                    $errors[] = 'Email already exists.';
                }
            }

            $initialMoney = 5000;
            if(!count($errors)) {
                $this->prepare('INSERT INTO users(username, email, password,  money) VALUES (?, ?, ?, ?)')
                    ->execute(
                        array(
                            $username,
                            $email,
                            password_hash($password, PASSWORD_DEFAULT),
                            $initialMoney
                        )
                    );

                if($this->getSTMT()->errorInfo()[0] != 0) {
                    $errors[] = 'Database error.';
                }
            }
        }

            return $errors;
    }

    public function login($username, $password)
    {
        $user = $this->prepare('SELECT * FROM users WHERE username = ?')
                    ->execute(array($username))
                    ->fetchRowAssoc();

        if($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return false;
    }

    public function getUserInfo($id)
    {
        return $this->prepare('SELECT u.id, u.username, u.email, u.money, r.name as role FROM users u INNER JOIN roles r ON u.role = r.id WHERE u.id = ?')
            ->execute(array($id))
            ->fetchRowAssoc();
    }
}