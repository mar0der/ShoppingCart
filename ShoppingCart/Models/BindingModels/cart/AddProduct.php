<?php


namespace DH\ShoppingCart\Models\BindingModels\Cart;

use DH\Mvc\BaseBindingModel;

class AddProduct extends  BaseBindingModel
{
    /**
     * [required]
     */
    public $quantity;
    /**
     * [required]
     */
    public $userProductId;
}