<?php
$config['default_controller']='index';
$config['default_method']='index';
$config['controllersSuffix']='Controller';
$config['namespaces']['My\ShoppingCart']='C:\xampp\htdocs\exs\mf\ShoppingCart';

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
