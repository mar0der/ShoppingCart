<?php
$config["DH\ShoppingCart\Controllers\Admin"]["products"]["add"]["DH\ShoppingCart\models\BindingModels\admin\products\AddProduct"] = ["productName" => [["minLength", "2"]], "category" => ["required"], "addCategory" => ["required"], "modelState" => [], "errors" => []];
$config["DH\ShoppingCart\Controllers\Admin"]["products"]["addtomarket"]["DH\ShoppingCart\Models\BindingModels\Admin\Products\AddToMarket"] = ["id" => ["required"], "quantity" => ["required"], "salePrice" => ["required"], "modelState" => [], "errors" => []];
$config["DH\ShoppingCart\Controllers"]["cart"]["add"]["DH\ShoppingCart\Models\BindingModels\Cart\AddProduct"] = ["quantity" => ["required"], "userProductId" => ["required"], "modelState" => [], "errors" => []];
$config["DH\ShoppingCart\Controllers"]["users"]["register"]["DH\ShoppingCart\Models\BindingModels\User\RegisterUser"] = ["username" => [["minLength", "2"]], "password" => [["minLength", "4"], ["matches", "{passwordAgain}"]], "email" => ["email"], "passwordAgain" => [], "modelState" => [], "errors" => []];
$config["DH\ShoppingCart\Controllers"]["users"]["login"]["DH\ShoppingCart\Models\BindingModels\User\LoginUser"] = ["username" => ["required", ["minLength", "2"]], "password" => ["required"], "modelState" => [], "errors" => []];

return $config;