<?php
namespace DH\Mvc;

class InputData
{
    private static $_instance = null;
    private $_get = null;
    private $_post = null;
    private $_cookies = null;

    private function __construct()
    {
        $this->_cookies = $_COOKIE;
    }

    public function setGet($get)
    {
        if (is_array($get)) {
            $this->_get = $get;
        }
    }

    public function setPost($post)
    {
        if (is_array($post)) {
            $this->_post = $post;
        }
    }

    public function hasGet($key)
    {
        if($this->_get == null) {
            return null;
        }

        return array_key_exists($key, $this->_get);
    }

    public function hasPost($key)
    {
        if($this->_post == null) {
            return null;
        }

        return array_key_exists($key, $this->_post);
    }

    public function hasCookies($key)
    {
        if($this->_cookies == null) {
            return null;
        }

        return array_key_exists($key, $this->_cookies);
    }

    public function get($id, $normalize = null, $default = null)
    {
        if($this->hasGet($id)) {
            if($normalize != null) {
                return \DH\Mvc\Common::normalize($this->_get[$id], $normalize);
            }

            return $this->_get[$id];
        }

        return $default;
    }

    public function post($name, $normalize = null, $default = null)
    {
        if($this->hasPost($name)) {
            if($normalize != null) {
                return \DH\Mvc\Common::normalize($this->_post[$name], $normalize);
            }

            return $this->_post[$name];
        }

        return $default;
    }

    public function cookies($name, $normalize = null, $default = null)
    {
        if($this->hasCookies($name)) {
            if($normalize != null) {
                return \DH\Mvc\Common::normalize($this->_cookies[$name], $normalize);
            }

            return $this->_cookies[$name];
        }

        return $default;
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \DH\Mvc\InputData();
        }

        return self::$_instance;
    }
}