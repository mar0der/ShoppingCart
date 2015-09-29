<?php

$config['admin']['namespace'] = 'Controllers\Admin1';

$config['administration']['namespace'] = 'Controllers\Admin2';
$config['administration']['controllers']['index'] = 'test';
$config['administration']['controllers']['new'] = 'create';
$config['*']['namespace'] = 'Controllers';

return $config;