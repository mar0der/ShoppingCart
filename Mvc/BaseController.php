<?php

namespace DH\Mvc;


class BaseController
{
    /**
     * @var  \DH\Mvc\App
     */
    public $app;
    /**
     * @var \DH\Mvc\View
     */
    public $view;
    /**
     * @var \DH\Mvc\Config
     */
    public $config;
    /**
     * @var \DH\Mvc\InputData
     */
    public $input;
    /**
     * @var \DH\Mvc\Session\ISession
     */
    public $session;

    public function __construct()
    {
        $this->app = \DH\Mvc\App::getInstance();
        $this->view = \DH\Mvc\View::getInstance();
        $this->config = \DH\Mvc\Config::getInstance();
        $this->input = \DH\Mvc\InputData::getInstance();
        $this->session = $this->app->getSession();
        View::logged((bool)$this->session->userId);
        View::role($this->session->user['role']);
    }

    public function jsonResponse()
    {
    }

    public function redirect($url) {
        header('Location: '  . $url);
    }

    public function isAdmin()
    {
        if($this->session->user['role'] == 3) {
            return true;
        }
        return false;
    }

    public function isEditor()
    {
        if($this->session->user['role'] == 2) {
            return true;
        }
        return false;
    }
}