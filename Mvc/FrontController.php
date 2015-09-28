<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 18.9.2015 ã.
 * Time: 13:05
 */

namespace GF;
class FrontController {
    private static $_instance = null;

    private function __construct() {

    }

    public function dispatch() {
        $router = new \GF\Routers\DefaultRouter();
        $router->getURI();
    }

    public function getDefaultController() {
        $app = \GF\App::getInstance();
        $controller = isset($app->getConfig()->app['default_controller']) ? $app->getConfig()->app['default_controller'] : null;
        if($controller) {
            return $controller;
        }

        return 'Index';
    }
    public function getDefaultMethod() {
        $app = \GF\App::getInstance();
        $method = isset($app->getConfig()->app['default_method']) ? $app->getConfig()->app['default_method'] : null;
        if($method) {
            return $method;
        }

        return 'Index';
    }

    /**
     * @return \GF\FrontController
     */
    public static function getInstance() {
        if(self::$_instance == null) {
            self::$_instance = new  \GF\FrontController();
        }

        return self::$_instance;
    }
}