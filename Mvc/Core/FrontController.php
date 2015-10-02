<?php

namespace My\Mvc\Core {


    class FrontController {

        private static $_instance = null;
        private $namespace = null;
        private $controller = null;
        private $method = null;
        /**
         * @var \My\Mvc\Config\Config
         */
        private $config = null;
        /**
         * @var \My\Mvc\Interfaces\IRouter;
         */
        private $router=null;

        private function __construct()
        {
            $this->config = \My\Mvc\Core\Application::getInstance()->getConfig();
        }

        public function getRouter() {
            return $this->router;
        }

        public function setRouter(\My\Mvc\Interfaces\IRouter $router) {
            $this->router = $router;
        }

        public function dispatch() {
            if ($this->router == null) {
                throw new \Exception('No valid router found',500);
            }
            $_uri = $this->router->getURI();
            $routes = \My\Mvc\Core\Application::getInstance()->getConfig()->routes;
            $routeController=null;
            if (is_array($routes) && count($routes) > 0) {
                foreach ($routes as $k => $v) {
                    if (stripos($_uri, $k) === 0 && ($_uri == $k || stripos($_uri, $k . '/') === 0) && $v['namespace']) {
                        $this->namespace = $v['namespace'];
                        $_uri = substr($_uri, strlen($k) + 1);
                        $routeController=$v;
                        break;
                    }
                }
            } else {
                throw new \Exception('Main route missing', 500);
            }

            if ($this->namespace == null && $routes['*']['namespace']) {
                $this->namespace = $routes['*']['namespace'];
                $routeController=$routes['*'];
            } else if ($this->namespace == null && !$routes['*']['namespace']) {
                throw new \Exception('Main route missing', 500);
            }
            $input = \My\Mvc\Core\InputData::getInstance();
            $params = explode('/', $_uri);
            if ($params[0]) {
                $this->controller= strtolower($params[0]);
                //if we do not have controller and method, we do not have params
                if ($params[1]) {
                    $this->method=strtolower($params[1]);
                    unset($params[0],$params[1]);
                    $input->setGet(array_values($params));
                } else {
                    $this->method=$this->getDefaultMethod();
                }
            } else {
                $this->controller= $this->getDefaultController();
                $this->method=$this->getDefaultMethod();
            }

            if(is_array($routeController) && $routeController['controllers']){
                if($routeController['controllers'][$this->controller]['methods'][$this->method]){
                    $this->method = strtolower($routeController['controllers'][$this->controller]['methods'][$this->method]);
                }
                if(isset($routeController['controllers'][$this->controller]['to'])){
                    $this->controller = strtolower($routeController['controllers'][$this->controller]['to']);
                }
            }

            $input->setPost($this->router->getPost());
            //TODO fix it
            $fullQualifiedName = $this->namespace . '\\' . ucfirst($this->controller) . $this->config->app['controllersSuffix'];
            $newControoler = new $fullQualifiedName();
            $newControoler->{$this->method}();
        }

        public function getDefaultController() {
            $controler = \My\Mvc\Core\Application::getInstance()->getConfig()->app['default_controller'];
            if ($controler) {
                return strtolower($controler);
            }
            return 'index';
        }

        public function getDefaultMethod() {
            $method = \My\Mvc\Core\Application::getInstance()->getConfig()->app['default_method'];
            if ($method) {
                return strtolower($method);
            }
            return 'index';
        }

        /**
         * @return \My\Mvc\Core\FrontController
         */
        public static function getInstance() {
            if (self::$_instance == null) {
                self::$_instance = new \My\Mvc\Core\FrontController();
            }
            return self::$_instance;
        }

    }
}