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

function createCustomRoutes($namespace)
{
    scanControllers('..\..', $controllers);
    $lines = '<?php'.PHP_EOL;
    foreach($controllers as $controller) {
        require_once $controller;

        if(!preg_match('/(base|Front)/i', $controller)) {
            preg_match_all('/(\w+)Controller/', $controller, $resultController);
            $controllerName=  strtolower($resultController[1][0]);
            preg_match_all('/(\w+)\.php/', $controller, $result);
            $className = $result[1][0];
            $className = $namespace. '\\' . $className;
            $reflection = new ReflectionClass($className);

            foreach($reflection->getMethods() as $method) {
                if(preg_match_all('/\[Route\("(.+?)"\)\]/', $method->getDocComment(), $routeResult)) {
                    $lines .= '$config["'.$routeResult[1][0].'"]["namespace"] = "'.$namespace.'";' . PHP_EOL;
                    $lines .= '$config["'.$routeResult[1][0].'"]["controllers"]["home"]["to"] = "' . $controllerName . '";' . PHP_EOL;
                    $lines .= '$config["'.$routeResult[1][0].'"]["controllers"]["home"]["methods"]["index"] = "' . $method->getName() . '";' . PHP_EOL;
                    $lines .= PHP_EOL;
                }
            }

            file_put_contents('../config/customRoutes.php', $lines);
        }
    }
}

createCustomRoutes('Controllers');
