<?php
$config['admin']['namespace']='Controllers\Admin';
$config['admin']['controllers']['index']['to']='test';
$config['admin']['controllers']['index']['methods']['new']['to']='_new';
$config['*']['namespace']='Controllers';

return $config;