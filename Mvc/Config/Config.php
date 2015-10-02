<?php

namespace My\Mvc\Config {

    class Config {

        private static $instance = null;
        private $configFolder = null;
        private $configArray = array();

        private function __construct() {
        }

        public function getConfigFolder() {
            return $this->configFolder;
        }

        public function setConfigFolder($configFolder) {
            if (!$configFolder) {
                throw new \Exception('Empty config folder path:');
            }
            $configFolderPath = realpath($configFolder);
            if ($configFolderPath != FALSE && is_dir($configFolderPath) && is_readable($configFolderPath)) {
                $this->configArray = array();
                $this->configFolder = $configFolderPath . DIRECTORY_SEPARATOR;
                $namespace = $this->app['namespaces'];
                if (is_array($namespace)) {
                    \My\Mvc\Core\AutoLoader::registerNamespaces($namespace);
                }
            } else {
                throw new \Exception('Config directory read error:' . $configFolder);
            }

        }

        public function includeConfigFile($path) {
            if (!$path) {
                throw new \Exception('Ivalid config path: '. $path);
            }
            $filePath = realpath($path);
            if ($filePath != FALSE && is_file($filePath) && is_readable($filePath)) {
                $basename = explode('.php', basename($filePath))[0];
                $this->configArray[$basename]=include $filePath;
            } else {
                throw new \Exception('Config file read error:' . $path);
            }
        }

        public function __get($name) {

            if (!$this->configArray[$name]) {
                $this->includeConfigFile($this->configFolder . $name . '.php');
            }
            if (array_key_exists($name, $this->configArray)) {
                return $this->configArray[$name];
            }
            return null;
        }

        /**
         *
         * @return \My\Mvc\Config\Config
         */
        public static function getInstance() {
            if (self::$instance == NULL) {
                self::$instance = new \My\Mvc\Config\Config();
            }
            return self::$instance;
        }

    }
}

