<?php

namespace DH\Mvc;


class View
{
    private static $_instance = null;
    private static $viewBag;
    private $__viewPath = null;
    private $__viewDir = null;
    private $__extension = '.php';
    private $model;
    private $__layoutParts = array();
    private $__layoutData = array();

    private function __construct()
    {
        $this->__viewPath = \DH\Mvc\App::getInstance()->getConfig()->app['viewsDirecotry'];
        if ($this->__viewPath == null) {
            $this->__viewPath = realpath('../views/');
        }
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new View();
        }

        return self::$_instance;
    }

    public function setViewDirectory($path)
    {
        $path = trim($path);
        if ($path) {
            $path = realpath($path) . DIRECTORY_SEPARATOR;
            if (is_dir($path) && is_readable($path)) {
                $this->__viewDir = $path;
            } else {
                throw new \Exception('view path');
            }
        } else {
            throw new \Exception('view path', 500);
        }
    }

    public function appendToLayout($key, $template)
    {
        if ($key && $template) {
            $this->__layoutParts[$key] = $template;
        } else {
            throw new \Exception('Layout require valid key and template.', 500);
        }
    }

    public function display($name, $model = null, $escape = true, $returnAsString = false)
    {
        if($escape) {
            $this->model = $this->escape($model);
        }else {
            $this->model = $model;
        }

        if (count($this->__layoutParts) > 0) {
            foreach ($this->__layoutParts as $key => $value) {
                $r = $this->_includeFile($value);
                if ($r) {
                    $this->__layoutData[$key] = $r;
                }
            }
        }

        if ($returnAsString) {
            return $this->_includeFile($name);
        } else {
            echo $this->_includeFile($name);
        }
    }

    public function getLayoutData($name)
    {
        return $this->__layoutData[$name];
    }

    private function _includeFile($name)
    {
        if ($this->__viewDir == null) {
            $this->setViewDirectory($this->__viewPath);
        }

        $__p = str_replace('.', DIRECTORY_SEPARATOR, $name);
        $__fl = $this->__viewDir . $__p . $this->__extension;
        if ($this->model != null) {
            try {
                $modelType = $this->getViewModelType($__fl);
                $ref = new \ReflectionClass($this->model);
            } catch (\Exception $ex) {
                throw new \Exception('Invalid model.', 500);
            }

            if ($ref->getName() != $modelType && strlen($modelType) > 0) {
                throw new \Exception('Invalid model.', 500);
            }
        }

        if (file_exists($__fl) && is_readable($__fl)) {
            ob_start();
            include $__fl;
            return ob_get_clean();
        } else {
            throw new \Exception('view' . $name . 'cannot be included', 500);
        }
    }

    private function getViewModelType($filePath)
    {
        $file = fopen($filePath, "r");
        $line = fgets($file);
        preg_match_all('/@var +(.+?) +\$/', $line, $matches);

        return trim($matches[1][0]);
    }

    private function escape($model)
    {
        if($model == null) {
            return null;
        }

        $type = gettype($model);
        if($type == 'array') {
            $temp = array();
            foreach($model as $key => $value){
                $temp[$key] = $this->escape($value);
            }

            return $temp;
        }

        if($type == 'object') {
            $reflection = new \ReflectionClass($model);

            if($reflection->getName() == 'stdClass') {
                foreach($model as $key => $value) {
                    $model->$key = $this->escape($value);
                }

                return $model;
            }
            foreach($reflection->getProperties() as $property){
                $property->setAccessible(true);
                $property->setValue($model, $this->escape($property->getValue($model)));
            }

            return $model;
        }

        return htmlspecialchars($model);
    }

    public static function __callStatic($methodName, $args)
    {
        if(count($args) > 0) {
            self::$viewBag[$methodName] = $args[0];
        } else {
            return self::$viewBag[$methodName];
        }
    }
}