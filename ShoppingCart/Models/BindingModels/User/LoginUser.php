<?php

namespace DH\ShoppingCart\Models\BindingModels\User;

class LoginUser extends \DH\Mvc\BaseBindingModel
{
    /**
     * [required]
     * [minLength(2)]
     */
    public $username;
    /**
     * [required]
     */
    public $password;
}