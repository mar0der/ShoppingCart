<?php

namespace My\Mvc;
include_once 'Loader.php';

class App{
    private static $_instance;
    private $_config = null;
    private $_frontController;

    private function __construct() {
        \My\Mvc\Loader::registerNamespace('My\Mvc', dirname(__FILE__).DIRECTORY_SEPARATOR);
        \My\Mvc\Loader::registerAutoLoad();
        $this->_config = \My\Mvc\Config::getInstance();
    }

    public function run(){
        if($this->_config->getConfigFolder() == null){
            $this->_config->setConfigFolder("../config");
        }

        $this->_frontController = \My\Mvc\FrontController::getInstance();

        $this->_frontController->dispatch();
    }

    /**
     * @return \My\Mvc\App
     */
    public static function getInstance(){
        if(self::$_instance == null) {
            self::$_instance = new \My\Mvc\App();
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