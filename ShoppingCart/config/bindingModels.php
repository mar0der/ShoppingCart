<?php
$config['DH\ShoppingCart\Controllers']['users']['login']['DH\ShoppingCart\Models\BindingModels\User\LoginUser'] = [
    'password' => ['required', ['minLength', 5]],
    'username' => ['required', ['minLength', 2]]
];

return $config;