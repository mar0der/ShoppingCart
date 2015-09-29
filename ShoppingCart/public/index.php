<?php
error_reporting(E_ALL ^ E_NOTICE);
echo '<pre>';
include '../../Mvc/App.php';
$app = \My\Mvc\App::getInstance();
$app->run();
