<?php
error_reporting(E_ALL ^ E_NOTICE);


function scanControllers($path, &$controllers)
{
    $dirs = scandir($path);

    foreach ($dirs as $entity) {
        if ($entity == '.' || $entity == '..') {
            continue;
        }

        $entity = $path . DIRECTORY_SEPARATOR . $entity;
        if (is_dir($entity)) {
            scanControllers($entity, $controllers);
        } else {
            if (preg_match('/Controller\.php/', $entity)) {
                $controllers[] = $entity;
            }
        }
    }
}

function createCustomRoutes($namespaces)
{
    scanControllers('..\..', $controllers);

    $lines = '<?php'.PHP_EOL;
    foreach($controllers as $controller) {
        require_once $controller;
        foreach($namespaces as $namespace) {
            if(!preg_match('/(base|Front)/i', $controller)) {
                preg_match_all('/(\w+)Controller/', $controller, $resultController);
                $controllerName=  strtolower($resultController[1][0]);
                preg_match_all('/(\w+)\.php/', $controller, $result);
                $className = $result[1][0];
                $className = $namespace. '\\' . $className;

                if(class_exists($className)) {
                    $reflection = new ReflectionClass($className);

                    preg_match_all('/\[RoutePrefix\("(.+?)"\)\]/', $reflection->getDocComment(), $routePrefixResult);
                    $routePrefix = $routePrefixResult[1][0];

                    if(substr($routePrefix, strlen($routePrefix) - 1) != '/' && strlen($routePrefix) > 0){
                        $lines .= '$route["'.$routePrefix.'"] = ["'.$namespace.'"];' . PHP_EOL;
                    }

                    foreach($reflection->getMethods() as $method) {
                        if(preg_match_all('/\[Route\("(.+?)"\)\]/', $method->getDocComment(), $routeResult)) {
                            $route = $routePrefix . $routeResult[1][0];
                            $lines .= '$route["'.$route.'"] = ["'.$namespace.'", "'.$controllerName.'", "'.$method->getName().'"];' . PHP_EOL;
                        }
                    }


                    break;
                }
            }
        }

        file_put_contents('../config/customRoutes.php', $lines);
    }
}

createCustomRoutes(
    array(
        'DH\ShoppingCart\Controllers',
        'DH\ShoppingCart\Controllers\Admin'
    ));
