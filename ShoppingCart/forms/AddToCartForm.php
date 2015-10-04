<?php

namespace DH\ShoppingCart\Forms;


use DH\Mvc\ViewHelpers\Form;
use DH\Mvc\ViewHelpers\Input;

class AddToCartForm
{
    public static function create($actionUrl, $userProductId)
    {
        $form = new Form('post', $actionUrl);
        $form
            ->setAttribute('class', 'addToCartForm')
            ->addElement(
                (new Input('number', 'quantity'))
                    ->setAttribute('placeholder', 'q:')
                    ->setAttribute('value', 1))
            ->addElement(
                (new Input('hidden', 'userProductId'))
                    ->setAttribute('value', $userProductId))
            ->addElement(
                (new Input('submit', 'submit'))
                    ->setAttribute('value', 'Add to cart'));

        return $form->render();
    }
}