<?php
$config['default_controller']='Index';
$config['default_method']='Index';
$config['controllersSuffix']='Controller';
$config['namespaces']['My\ShoppingCart']='C:\xampp\htdocs\exs\mf\ShoppingCart';
$config['defaultControllerNamespace'] = 'My\ShoppingCart\Controllers';

$config['session']['autostart'] = true;
$config['session']['type'] = 'database';
$config['session']['name'] = '__sess';
$config['session']['lifetime'] = 3600;
$config['session']['path'] = '/';
$config['session']['domain'] = '';
$config['session']['secure'] = false;
$config['session']['dbConnection'] = 'default';
$config['session']['dbTable'] = 'sessions';
return $config;

