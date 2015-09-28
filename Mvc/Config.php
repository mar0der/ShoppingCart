<?php

namespace GF;
class Config {
    private static $_instance = null;
    private $_configFolder = null;
    private $_configArray = array();

    public function __construct() {

    }

    public function getConfigFolder() {
        return $this->_configFolder;
    }

    public static function getInstance() {
        if(self::$_instance == null){
            self::$_instance = new \GF\Config();
        }

        return self::$_instance;
    }

    public function setConfigFolder($configFolder) {
        if(!$configFolder) {
            throw new \Exception('Empty config folder path.');
        }

        $_configFolder = realpath($configFolder);
        if($_configFolder != false && is_dir($_configFolder) && is_readable($_configFolder)) {
            //clear old config data
            $this->_configArray = array();
            $this->_configFolder = $_configFolder . DIRECTORY_SEPARATOR;
//            $namespaces = $this->app['namespaces'];
//            if(is_array($namespaces)) {
//                \GF\Loader::registerNamespaces($namespaces);
//            }
        } else {
            throw new \Exception('Config directory read error: ' . $configFolder);
        }
    }

    public function __get($name) {
        if(!isset($this->_configArray[$name])) {
            $this->includeConfigFile($this->_configFolder . $name . '.php');
        }

        if(array_key_exists($name, $this->_configArray)) {
            return $this->_configArray[$name];
        }

        return null;
    }

    public function includeConfigFile($path) {
        if(!$path) {
            throw new \Exception;
        }

        $_file = realpath($path);
        if($_file != false && is_file($_file) && is_readable($_file)) {
            $_basename = explode('.php', basename($_file))[0];
            $this->_configArray[$_basename] = include $_file;
        }else {
            throw new \Exception('Canfig file read error: '. $path);
        }
    }
}