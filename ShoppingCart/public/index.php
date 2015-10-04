<?php
error_reporting(E_ALL ^ E_NOTICE);

require '../../MVC/App.php';
$app = \DH\Mvc\App::getInstance();
$app->run();

