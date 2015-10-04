<?php

namespace DH\ShoppingCart\Controllers\Admin;
use DH\Mvc\BaseController;

/**
 * [RoutePrefix("admina/")]
 */
class HomeController extends BaseController
{
    /**
     * [Route("neshtosi")]
     */
    public function Index(){
        if(!$this->isAdmin()) {
            $this->redirect('/profile');
        }
    }
}