<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/2/2015
 * Time: 1:43 PM
 */

namespace My\Mvc\Interfaces {


    interface IRouter
    {
        public function getURI();
        public function getPost();
    }
}