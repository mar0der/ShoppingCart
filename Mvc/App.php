<?php

namespace GF;
include_once 'Loader.php';
class App{
    private static $_instance;
    private $_config = null;
    private $_frontController;

    private function __construct() {
        \GF\Loader::registerNamespace('GF', dirname(__FILE__).DIRECTORY_SEPARATOR);
        \GF\Loader::registerAutoLoad();
        $this->_config = \GF\Config::getInstance();
    }

    public function run(){
        if($this->_config->getConfigFolder() == null){
            $this->_config->setConfigFolder("../config");
        }

        $this->_frontController = \GF\FrontController::getInstance();

        $this->_frontController->dispatch();
    }

    /**
     * @return \GF\App
     */
    public static function getInstance(){
        if(self::$_instance == null) {
            self::$_instance = new \GF\App();
        }

        return self::$_instance;
    }

    public function setConfigFolder($path) {
        $this->config.$this->setConfigFolder($path);
    }

    public function getConfigFolder() {
        return $this->config->getConfigFolder();
    }

    /**
     * @return Config|null
     */
    public function getConfig() {
        return $this->_config;
    }
}