<?php

namespace DH\ShoppingCart\models\BindingModels\admin\products;


class AddProduct extends \DH\Mvc\BaseBindingModel
{
    /**
     * [minLength(2)]
     */
    public $productName;
    /**
     * [required]
     */
    public $category;
    /**
     * [required]
     */
    public $addCategory;
}