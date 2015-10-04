<?php

namespace DH\ShoppingCart\Models;


use DH\Mvc\DB\SimpleDB;

class CategoriesModel extends SimpleDB
{
    public function getAllCategories()
    {
        $allCategories = $this->prepare("SELECT `id`, `name` FROM `categories`");
        $allCategories->execute();
        $allCategories  = $allCategories->fetchAllAssoc();
        if(!$allCategories){
            throw new \Exception('Cannot get all categories', 500);
        }

        return $allCategories;
    }
}