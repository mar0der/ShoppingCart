<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/3/2015
 * Time: 6:27 PM
 */
error_reporting(E_ALL ^ E_NOTICE);
require_once '../../mvc/Loader.php';

\My\Mvc\Core\AutoLoader::registerNamespace('DH\Mvc', realpath('../../mvc'));
\My\Mvc\Core\AutoLoader::registerNamespace('DH\ShoppingCart', realpath('../../ShoppingCart'));
\My\Mvc\Core\AutoLoader::registerAutoLoad();

function scanModels($path, &$models)
{
    $dirs = scandir($path);

    foreach ($dirs as $entity) {
        if ($entity == '.' || $entity == '..') {
            continue;
        }

        $entity = $path . DIRECTORY_SEPARATOR . $entity;

        if (is_dir($entity)) {
            scanModels($entity, $models);
        } else {
            if (preg_match('/\.php/', $entity)) {
                $models[] = $entity;
            }
        }
    }
}


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

function generateBindingRules($controllerStartPath, $configFilePath)
{
    scanControllers($controllerStartPath, $controllers);
    $configVar = '$config';
    $buffer = '<?php' . PHP_EOL;
    foreach ($controllers as $controller) {
        if (!preg_match('/(base|Front)/i', $controller)) {
            preg_match_all('/(\w+)Controller/', $controller, $resultController);
            $controllerName = strtolower($resultController[1][0]);
            preg_match_all('/(\w+)\.php/', $controller, $result);
            $namespace = getClassNamespace($controller);
            $className = $namespace . '\\' . $result[1][0];

            if (class_exists($className)) {
                $reflection = new ReflectionClass($className);
                foreach($reflection->getMethods() as $method) {
                    foreach($method->getParameters() as $param) {
                        $class = $param->getClass();
                        if($class) {
                            $tempBuffer = $configVar . '["'.$namespace.'"]["'.$controllerName.'"]';
                            $tempBuffer .= '["'.$method->getName().'"]["'.$class->getName().'"] = [';
                            $hasAttributes = false;
                            foreach($class->getProperties() as $prop) {
                                $parsedAttributes = parseAttribute($prop->getDocComment());
                                if($parsedAttributes) {
                                    $hasAttributes = true;
                                    $tempBuffer .= '"' . $prop->getName(). '" => [';
                                    foreach($parsedAttributes as $attr) {
                                        if(!$attr['params']) {
                                            $tempBuffer .= '"'.$attr['method'].'", ';
                                        }
                                        else{
                                            $stringParams = '';
                                            foreach($attr['params'] as $attrParam) {
                                                $stringParams .= '"'.$attrParam.'", ';
                                            }
                                            $stringParams = substr($stringParams, 0, strlen($stringParams) - 2);
                                            $tempBuffer .= '["'.$attr['method'].'", '.$stringParams. '], ';
                                        }
                                    }
                                    $tempBuffer = substr($tempBuffer, 0, strlen($tempBuffer) - 2);
                                    $tempBuffer .= '], ';
                                }

                            }
                            if($hasAttributes) {
                                $tempBuffer = substr($tempBuffer, 0, strlen($tempBuffer) - 2);
                                $tempBuffer .= '];'. PHP_EOL;
                                echo $tempBuffer;
                                $buffer .= $tempBuffer;
                            }
                        }
                    }
                }
            }
        }
    }
    $buffer .= PHP_EOL . 'return ' . $configVar . ';';
    file_put_contents($configFilePath, $buffer);
}

generateBindingRules('..\..', '..\config\bindingModels.php');
function getClassNamespace($file)
{
    $content = file_get_contents($file);
    preg_match_all('/([\r\n])+?namespace +(.+?);/', $content, $result);

    return $result[2][0];
}

function parseAttribute($doc)
{
    $isMatched = preg_match_all('/\[(\w+)(\((.+?)\))?\]/', $doc, $result);
    if (!$isMatched) {
        return false;
    }

    $return = [];

    for ($i = 0; $i < count($result[1]); $i++) {
        $attr = array(
            'method' => $result[1][$i],
        );
        if ($result[3][$i]) {
            $params = explode(',', $result[3][$i]);
            $attr['params'] = $params;
        }

        $return[] = $attr;
    }

    return $return;
}
