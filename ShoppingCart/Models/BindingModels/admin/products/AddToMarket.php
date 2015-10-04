<?php

namespace DH\ShoppingCart\Models\BindingModels\Admin\Products;


class AddToMarket extends \DH\Mvc\BaseBindingModel
{
    /**
     * [required]
     */
    public $id;
    /**
     * [required]
     */
    public $quantity;
    /**
     * [required]
     */
    public $salePrice;
}