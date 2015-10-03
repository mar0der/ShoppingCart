<?php

namespace My\Mvc\Core;

use My\Mvc\Interfaces\IRouter;
use My\ShoppingCart\Models\BindingModels\User\LoginUser;

class FrontController
{
    private static $_instance = null;
    private $namespace = null;
    private $controller = null;
    private $method = null;
    private $router = null;
    /**
     * @var \My\Mvc\Config\Config
     */
    private $config = null;

    /**
     * @return \My\Mvc\Interfaces\IRouter
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param \My\Mvc\Interfaces\IRouter $router
     */
    public function setRouter(\My\Mvc\Interfaces\IRouter $router)
    {
        $this->router = $router;
    }

    private function __construct()
    {
        $this->config = \My\Mvc\Core\Application::getInstance()->getConfig();
    }

    public function dispatch()
    {
        if($this->router == null) {
            throw new \Exception('No valid router found.', 500);
        }
        $uri = $this->router->getURI();
        $input = \My\Mvc\Core\InputData::getInstance();

        if($this->parseCustomRoute($input, $uri)){
            return;
        }

        $params = explode('/', $uri);
        $this->controller = array_shift($params);
        if(!$this->controller) {
            $this->controller = $this->getDefaultController();
            $this->method = $this->getDefaultMethod();
        } else {
            $this->method = array_shift($params);
            if(!$this->method) {
                $this->method = $this->getDefaultMethod();
            }
            $input->setGet($params);
        }
        $this->namespace = $this->config->app['defaultControllerNamespace'];

        $input->setPost($this->router->getPost());
        $this->loadControllerAction();
    }

    public function getDefaultController()
    {
        $controller = $this->config->app['default_controller'];
        if ($controller) {
            return strtolower($controller);
        }

        return 'Index';
    }

    public function getDefaultMethod()
    {
        $method = $this->config->app['default_method'];
        if ($method) {
            return strtolower($method);
        }

        return 'Index';
    }

    /**
     * @return \My\Mvc\Core\FrontController
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new  \My\Mvc\Core\FrontController();
        }

        return self::$_instance;
    }

    public function loadControllerAction()
    {
        $controllerPrefix = $this->controller;
        $this->controller = $this->controller . 'Controller';
        $namespaceClass = $this->namespace . '\\' . ucfirst($this->controller);

        $isCallable = false;
        try {
//            v($namespaceClass);
//            v($this->method);
            $isCallable = is_callable(array($namespaceClass, $this->method));
        } catch (\Exception $ex) {
            throw new \Exception('Not found', 404);
        }
        v($this->namespace);
        v($this->method);
        if ($isCallable) {
            $bindingModel = null;
//            if($this->router->getPost()) {
//                $bindingModel = BindingModel::validate($this->router->getPost(),
//                    $this->config->bindingModels[$this->namespace][$controllerPrefix][$this->method]);
//            }

            $newController = new $namespaceClass();

            call_user_func(array($newController, $this->method), $bindingModel);
        } else {
            throw new \Exception('Not found', 404);
        }
    }

    public function parseCustomRoute($input, $uri)
    {
        $customRoutes = $this->config->routes;
        if($customRoutes && is_array($customRoutes)) {
            foreach($customRoutes as $customRoute => $controllerAction) {
                $routeParts = explode('/{', $customRoute);
                $matchedCustomRoute = array_shift($routeParts);
                $hasParams = preg_match_all('/{(.+?)}/i', $customRoute, $result);
                $routeParamNames = $result[1];

                if(stripos($uri, $matchedCustomRoute) === 0
                    && ($uri == $matchedCustomRoute || stripos($uri, $matchedCustomRoute . '/') === 0)) {

                    if($hasParams) {
                        $paramsUri = substr($uri, strlen($matchedCustomRoute) + 1);
                        $paramValues = explode('/', $paramsUri);
                        $params = array();
                        foreach($routeParamNames as $paramName) {
                            $params[$paramName] = array_shift($paramValues);
                        }

                        $input->setGet($params);
                    }


                    if($hasParams) {
                        $matchedCustomRoute = substr($matchedCustomRoute, strlen($customRoute) + 1);
                    } else {
                        $matchedCustomRoute = substr($uri, strlen($customRoute) + 1);
                    }

                    $matchedCustomRouteArray = explode('/', $matchedCustomRoute);

                    if($controllerAction[1]) {
                        $this->controller = $controllerAction[1];
                    } else {
                        $this->controller = array_shift($matchedCustomRouteArray);
                        if(!$this->controller) {
                            $this->controller = $this->getDefaultController();
                        }
                    }
                    if($controllerAction[2]) {
                        $this->method = $controllerAction[2];
                    } else {
                        $this->method = array_shift($matchedCustomRouteArray);
                        if(!$this->method) {
                            $this->method = $this->getDefaultMethod();
                        }
                    }
                    $input->setPost($this->router->getPost());
                    $this->namespace = $controllerAction[0] ? $controllerAction[0] : $this->config->app['defaultControllerNamespace'];
                    $this->loadControllerAction();
                    return true;
                }
            }
        }
    }
}