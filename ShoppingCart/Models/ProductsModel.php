<?php

namespace DH\ShoppingCart\models;


use DH\Mvc\DB\SimpleDB;

class ProductsModel extends SimpleDB
{
    public function __construct()
    {
        parent::__construct('default');
    }

    public function addProduct(\DH\ShoppingCart\models\BindingModels\admin\products\AddProduct $data)
    {
        $existingCategory = $this->prepare("SELECT `id` FROM `categories` WHERE `id` = ?");
        $existingCategory->execute([$data->category]);
        if(!$existingCategory){
            throw new \Exception('There is no such categories');
        }

        $result = $this->prepare("INSERT INTO `shopping_cart`.`products` (`id`, `name`, `category_id`) VALUES (NULL, ?, ?)");
        $result->execute([
            $data->productName,
            $data->category
        ]);
        if(!$result->getAffectedRows()){
            throw new \Exception('Unable to add product!');
        };
    }

    public function getAllProductsForSaleFromCategory($categoryName, $userId)
    {
        $allProducts = $this->prepare("
            SELECT
              `quantity`,`sell_price`, p.name, u.username
            FROM
              `users_products` as up
            INNER JOIN users u ON u.id = up.user_id
            INNER JOIN products p ON p.id = up.product_id
            INNER JOIN categories c ON c.id = p.category_id
            WHERE `status` = 1 AND c.name = ? AND u.id != ?");

        return $allProducts->execute(array($categoryName, $userId))->fetchAllAssoc();
    }

    public function getAllProductsForSale($userId)
    {
        $allProducts = $this->prepare("
            SELECT
              up.id, `quantity`,`sell_price`, p.name, u.username
            FROM
              `users_products` as up
            INNER JOIN users u ON u.id = up.user_id
            INNER JOIN products p ON p.id = up.product_id
            WHERE `status` = 1 AND up.user_id != ?");

        return $allProducts->execute(array($userId))->fetchAllAssoc();
    }

    public function getUserProducts($userId)
    {
        $allProducts = $this->prepare("
            SELECT
              up.id, `quantity`,`sell_price`, p.name, u.username, status, u.username as seller
            FROM
              `users_products` up
            INNER JOIN users u ON u.id = up.user_id
            INNER JOIN products p ON p.id = up.product_id
            WHERE u.id = ?");

        return $allProducts->execute(array($userId))->fetchAllAssoc();
    }

    public function getAllProducts()
    {
        $allProducts = $this->prepare("SELECT p.id, p.name, c.name as category FROM `products` p INNER JOIN categories c ON p.category_id = c.id ");
        $allProducts->execute();
        $allProducts = $allProducts->fetchAllAssoc();
        if(!$allProducts){
            throw new \Exception('Cannot load products');
        }
        return $allProducts;
    }

    public function getProductById($id)
    {
        $product = $this->prepare("SELECT * FROM products WHERE id = ?");
        $product->execute([$id]);
        $product = $product->fetchRowAssoc();

        if(!$product){
            throw new \Exception('Cannot find such product');
        }
        return $product;
    }

    public function addToMarket(\DH\ShoppingCart\models\BindingModels\Admin\Products\AddToMarket $productData, $userId )
    {
        $existingUser = $this->prepare("SELECT * FROM users WHERE id = ?");
        $existingUser->execute([$userId]);
        if(count($existingUser->fetchRowAssoc()) == 0){
            throw new \Exception('No user logged in');
        }
        $result = $this->prepare('INSERT INTO `shopping_cart`.`users_products` (`id`, `user_id`, `product_id`, `sell_price`, `quantity`, `status`) VALUES (NULL, ?, ?, ?, ?, ?)');
        $result->execute(
            [
                $userId,
                $productData->id,
                $productData->salePrice,
                $productData->quantity,
                '1'
            ]);

        if(!$result->getAffectedRows()){
            throw new \Exception('Unable to put this for sale');
        }
    }
}