<?php

namespace DH\ShoppingCart\Forms\Admin\Products;

use DH\Mvc\ViewHelpers\Dropdown;
use DH\Mvc\ViewHelpers\Form;
use DH\Mvc\ViewHelpers\Input;

class AddProduct
{
    public static function create($categoryData, $productName = '', $quantity = '', $categorySelected = 1)
    {
        $form = new Form();
        $form->addElement(
            (new Input('text', 'productName'))
                ->setAttribute('placeholder', 'Product Name')
                ->setAttribute('value', $productName))
            ->addElement(
                (new Dropdown('category', $categoryData))
                    ->setSelectedOption($categorySelected))
            ->addElement(
                (new Input('submit', 'addCategory'))
                    ->setAttribute('value', 'Add Product'));
        return $form->render();
    }
}
