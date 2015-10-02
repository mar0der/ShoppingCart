<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/2/2015
 * Time: 1:45 PM
 */

namespace My\Mvc\Routers {


    class DefaultRouter implements \My\Mvc\Interfaces\IRouter
    {

        public function getURI()
        {
            if(isset($_GET['uri'])){
                return $_GET['uri'];
            }
            return '';
        }

        public function getPost()
        {
            // TODO: Implement getPost() method.
        }
    }
}