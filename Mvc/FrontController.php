<?php

namespace My\Mvc;
class FrontController {
    private static $_instance = null;

    private function __construct() {

    }

    public function dispatch() {
        $router = new \My\Mvc\Routers\DefaultRouter();
        echo $router->getURI();
    }

    public function getDefaultController() {
        $app = \My\Mvc\App::getInstance();
        $controller = isset($app->getConfig()->app['default_controller']) ? $app->getConfig()->app['default_controller'] : null;
        if($controller) {
            return $controller;
        }

        return 'Index';
    }
    public function getDefaultMethod() {
        $app = \My\Mvc\App::getInstance();
        $method = isset($app->getConfig()->app['default_method']) ? $app->getConfig()->app['default_method'] : null;
        if($method) {
            return $method;
        }

        return 'Index';
    }

    /**
     * @return \My\Mvc\FrontController
     */
    public static function getInstance() {
        if(self::$_instance == null) {
            self::$_instance = new  \My\Mvc\FrontController();
        }

        return self::$_instance;
    }
}