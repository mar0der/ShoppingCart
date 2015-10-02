<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/2/2015
 * Time: 1:04 PM
 */

namespace My\Mvc\Core;

use My\Mvc\Routers\DefaultRouter;

require_once('..'.DS.'..'.DS.'Mvc'.DS.'Config'.DS.'Constants.php');
require_once('..'.DS.'..'.DS.'Mvc'.DS.'Core'.DS.'Debug.php');
require_once('..'.DS.'..'.DS.'Mvc'.DS.'Core'.DS.'AutoLoader.php');

class Application
{
    private static $instance = null;

    private $config;
    /**
     * @var \My\Mvc\Core\FrontController
     */
    private $frontController;
    /**
     * @var \My\Mvc\Interfaces\IRouter
     */
    private $router;

    private function __construct()
    {
        session_start();
        \My\Mvc\Core\AutoLoader::registerNamespace(VENDOR_NAMESPACE, $this->getVendorNamespacePath());
        \My\Mvc\Core\AutoLoader::registerAutoLoad();

        $this->config = \My\Mvc\Config\Config::getInstance();
        if ($this->config->getConfigFolder() == null) {
            $this->config->setConfigFolder('../config');
        }
    }

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new Application();
        }

        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    public function run() {
        $this->frontController = \My\Mvc\Core\FrontController::getInstance();
        if ($this->router instanceof \My\Mvc\Interfaces\IRouter) {
            $this->frontController->setRouter($this->router);
        } else if ($this->router == 'JsonRPCRouter') {
            //TODO fix it when RPC is done
            $this->frontController->setRouter(new \My\Mvc\Routers\DefaultRouter());
        } else if ($this->router == 'CLIRouter') {
            //TODO fix it when RPC is done
            $this->frontController->setRouter(new \My\Mvc\Routers\DefaultRouter());
        } else {
            $this->frontController->setRouter(new \My\Mvc\Routers\DefaultRouter());
        }

//        $_sess = $this->_config->app['session'];
//        if ($_sess['autostart']) {
//            if ($_sess['type'] == 'native') {
//                $_s = new \GF\Session\NativeSession($_sess['name'], $_sess['lifetime'], $_sess['path'], $_sess['domain'], $_sess['secure']);
//            } else if ($_sess['type'] == 'database') {
//                $_s = new \GF\Session\DBSession($_sess['dbConnection'],
//                    $_sess['name'], $_sess['dbTable'], $_sess['lifetime'], $_sess['path'], $_sess['domain'], $_sess['secure']);
//            } else {
//                throw new \Exception('No valid session', 500);
//            }
//            $this->setSession($_s);
//        }

        $this->frontController->dispatch();
    }

    /**
     * @return array|string
     */
    private function getVendorNamespacePath()
    {
        $mainNamespacePath = explode(DS, dirname(__FILE__));
        array_pop($mainNamespacePath);
        $mainNamespacePath = implode(DS, $mainNamespacePath) . DS;
        return $mainNamespacePath;
    }

    public function getRouter() {
        return $this->router;
    }

    public function setRouter($router) {
        $this->router = $router;
    }

    /**
     *
     * @return \My\Mvc\Config\Config
     */
    public function getConfig() {
        return $this->config;
    }
}