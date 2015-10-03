<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/3/2015
 * Time: 9:36 AM
 */

namespace My\ShoppingCart\Models\ViewModels\Index {


    class Index
    {
        private $body;

        public function getBody()
        {
            return $this->body;
        }
        public function setBody($body)
        {
            $this->body = $body;
        }
    }
}