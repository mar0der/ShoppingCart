<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10/2/2015
 * Time: 12:55 PM
 */
error_reporting(E_ALL ^ E_NOTICE);
echo '<pre>';
const DS = DIRECTORY_SEPARATOR;

require_once('..'.DS.'..'.DS.'Mvc'.DS.'Core'.DS.'Application.php');

/**
 * @var \My\Mvc\Core\Application
 */
$app = \My\Mvc\Core\Application::getInstance();

$app->Run();
