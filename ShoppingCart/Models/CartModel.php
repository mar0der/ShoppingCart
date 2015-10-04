<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 4.10.2015 ã.
 * Time: 6:04
 */

namespace DH\ShoppingCart\Models;


use DH\Mvc\Common;
use DH\Mvc\DB\SimpleDB;
use DH\ShoppingCart\Models\BindingModels\Cart\AddProduct;

class CartModel extends SimpleDB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addProduct(AddProduct $model, $shopperId)
    {
        $userProductId = Common::normalize($model->userProductId, 'int');
        $quantity = Common::normalize($model->quantity, 'int');

        $addStmt = $this->prepare('INSERT INTO `shopping_cart`(`shopper_id`, `user_product_id`, `quantity`) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + ?');
        $addStmt->execute(
            array(
                $shopperId,
                $userProductId,
                $quantity,
                $quantity
            )
        );
    }

    public function getProducts($userId)
    {
        $products = $this->prepare('
                SELECT
                    up.id user_product_id, p.name, sc.quantity, u.username seller, up.sell_price, (up.sell_price * sc.quantity) totalPrice,
                    u.id sellerId, up.product_id
                FROM
                  `shopping_cart` sc
                  INNER JOIN `users_products` up ON up.id = sc.user_product_id
                  INNER JOIN  `products` p ON p.id = up.product_id
                  INNER JOIN  `users` u ON u.id = up.user_id
                WHERE
                  shopper_id = ?')
            ->execute(array($userId))
            ->fetchAllAssoc();

        return $products;
    }

    public function removeProduct($productId, $userId)
    {
        $this->prepare('DELETE FROM `shopping_cart` WHERE `user_product_id` = ? AND `shopper_id` = ?')
            ->execute(array($productId, $userId));
    }

    public function checkOut($user)
    {
        $cartItems = $this->getProducts($user['id']);
        foreach($cartItems as $item) {
            $itemQuantity = $this->prepare('SELECT quantity FROM users_products WHERE id = ?')
                ->execute(array($item['user_product_id']))
                ->fetchRowAssoc()['quantity'];
            if($item['totalPrice'] > $user['money']) {
                $errors[] = 'not enouhg money.';
            }
            if($itemQuantity < $item['quantity']){
                $errors[] = 'not enough product quantity.';
            }

            if(count($errors)) {
                return $errors;
            }

            $this->getDB()->beginTransaction();
            try {
                $this->prepare('UPDATE users_products SET quantity = quantity - ? WHERE id = ?')
                    ->execute(array($item['quantity'], $item['user_product_id']));
                $this->prepare('UPDATE users SET money = money - ? WHERE id = ?')
                    ->execute(array($item['totalPrice'],$user['id']));
                $this->prepare('UPDATE users SET money = money + ? WHERE id = ?')
                    ->execute(array($item['totalPrice'], $item['sellerId']));
                $this->prepare('INSERT INTO users_products(user_id, product_id, sell_price, quantity, status)
                VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE
                quantity = quantity + ?')
                    ->execute(array($user['id'], $item['product_id'], $item['sell_price'], 1, 2, $item['quantity']));
                $this->prepare('DELETE FROM shopping_cart WHERE user_product_id = ? AND shopper_id = ?')
                    ->execute(array($item['user_product_id'], $user['id']));
                $this->getDB()->commit();
            }catch (\Exception $ex) {
                $this->getDB()->rollBack();
            }
        }

        return $errors;
    }
}