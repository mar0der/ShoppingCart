<?php

namespace DH\ShoppingCart\Models\BindingModels\User;


class RegisterUser extends \DH\Mvc\BaseBindingModel
{
    /**
     * [minLength(2)]
     */
    public $username;
    /**
     * [minLength(4)]
     * [matches({passwordAgain})]
     */
    public $password;
    /**
     * [email]
     */
    public $email;
    public $passwordAgain;
}