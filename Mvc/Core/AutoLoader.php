<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/2/2015
 * Time: 12:56 PM
 */

namespace My\Mvc\Core {


    class AutoLoader
    {
        private static $namespaces = array();

        private function __construct() {

        }

        public static function registerAutoLoad() {
            spl_autoload_register(["My\Mvc\Core\AutoLoader", 'autoload']);
        }

        public static function autoload($class) {
            self::loadClass($class);
        }

        //TODO: central error reporting
        public static function loadClass($class) {
            foreach (self::$namespaces as $k => $v) {
                if (strpos($class, $k) === 0) {
                    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
                    $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
                    $file = substr_replace($file, $v, 0 , strlen($k));
                    $file = $file . '.php';

                    if ($file && is_readable($file)) {
                        include $file;
                    } else {
                        throw new \Exception('File cannot be included:' . $file);
                    }
                    break;
                }
            }
        }

        public static function registerNamespace($namespace, $path) {
            $namespace = trim($namespace);
            if (strlen($namespace) > 0) {
                if(!$path){
                    throw new \Exception('Invalid path');
                }
                $_path = realpath($path);
                if ($_path && is_dir($_path) && is_readable($_path)) {
                    self::$namespaces[$namespace.'\\'] = $_path . DIRECTORY_SEPARATOR;
                } else {
                    throw new \Exception('Namespace directory read error:' . $path);
                }
            } else {
                throw new \Exception('Invalid namespace:' . $namespace);
            }
        }

        public static function registerNamespaces($namespacesArray) {
            if (is_array($namespacesArray)) {
                foreach ($namespacesArray as $k => $v) {
                    self::registerNamespace($k, $v);
                }
            } else {
                throw new \Exception('Invalid namespaces');
            }
        }

        public static function getNamespaces() {
            return self::$namespaces;
        }

        public static function removeNamespace($namespace) {
            unset(self::$namespaces[$namespace]);
        }

        public static function clearNamespaces() {
            self::$namespaces = array();
        }

    }
}