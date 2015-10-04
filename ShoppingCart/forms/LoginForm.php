<?php
namespace DH\ShoppingCart\Forms;

use DH\Mvc\ViewHelpers\Form;
use DH\Mvc\ViewHelpers\Input;

class LoginForm
{
    public static function create($username = '')
    {
        $form = new Form();
        $form->addElement(
            (new Input('text', 'username'))
                ->setAttribute('placeholder', 'Username')
                ->setAttribute('value', $username))
            ->addElement(
                (new Input('password', 'password'))
                    ->setAttribute('placeholder', 'Password'))
            ->addElement(
                (new Input('submit', 'submit'))
                    ->setAttribute('value', 'Login'));

        return $form->render();
    }
}