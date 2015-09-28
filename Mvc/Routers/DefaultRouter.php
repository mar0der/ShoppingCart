<?php

namespace GF\Routers;
class DefaultRouter implements \GF\Routers\IRouter{
    private $_controller = null;
    private $_method = null;
    private $_params = array();

    public function getURI() {
        return substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']) + 1);
    }
}