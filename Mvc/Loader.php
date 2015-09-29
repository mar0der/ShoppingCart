<?php

namespace My\Mvc;

final class Loader
{

    private static $namespaces = array();

    private function __construct()
    {

    }

    public static function registerAutoLoad()
    {
        spl_autoload_register(array('\My\Mvc\Loader', 'autoload'));
    }

    public static function autoload($class)
    {
        self::loadClass($class);
    }

    public static function registerNamespace($namespace, $path)
    {
        $namespace = trim($namespace);
        if (strlen($namespace) > 0) {
            if (!$path) {
                throw new \Exception("Invalid path");
            }

            $_path = realpath($path);
            if ($_path && is_dir($_path) && is_readable($_path)) {
                self::$namespaces[$namespace . '\\'] = $_path . DIRECTORY_SEPARATOR;
            } else {
                //TODO
                throw new \Exception('Namespace directory read error: ' . $path);
            }
        } else {
            //TODO
            throw new \Exception("Invalid namespace");
        }
    }

    public static function registerNamespaces($namespacePaths)
    {
        if (is_array($namespacePaths)) {
            foreach ($namespacePaths as $namespace => $path) {
                self::registerNamespace($namespace, $path);
            }
        } else {
            throw new \Exception('Invalid namespaces config.');
        }
    }

    public static function loadClass($class) {
        foreach(self::$namespaces as $namespace => $path) {
            if(strpos($class, $namespace) === 0) {
                $fullFilePath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
                $fullFilePath = substr_replace($fullFilePath, $path, 0, strlen($namespace)).'.php';
                $fullFilePath = realpath($fullFilePath);
                if($fullFilePath && is_readable($fullFilePath)) {
                    include $fullFilePath;
                }else {
                    throw new \Exception('File cannot be included: ' . $fullFilePath);
                }

                break;
            }
        }
    }

    public static  function getNamespaces() {
        return self::$namespaces;
    }

    public static function removeNamespace($namespace) {
        unset(self::$namespaces[$namespace]);
    }

    public static function clearNamespaces() {
        self::$namespaces = array();
    }
}