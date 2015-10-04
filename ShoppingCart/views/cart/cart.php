<?php /** @var DH\ShoppingCart\Models\ViewModels\Cart\CartView $model */ ?>
<?php if(count($this->model->products) > 0): ?>
    <table>
        <thead>
        <tr>
            <td>Product name</td>
            <td>Seller</td>
            <td>Quantity</td>
            <td>Price</td>
            <td>Total price</td>
            <?php if(\DH\Mvc\View::logged()) : ?>
                <td class="addToCartColumn"></td>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($this->model->products as $product) :  ?>
            <tr>
                <td><?= $product['name'] ?></td>
                <td><?= $product['seller'] ?></td>
                <td><?= $product['quantity'] ?></td>
                <td>$<?= $product['sell_price'] ?></td>
                <td>$<?= $product['totalPrice'] ?></td>
                <td><a href="/cart/remove/<?= $product['user_product_id']; ?>">remove</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/cart/checkout">Check out</a>
<?php else : ?>
    <p>No products</p>
<?php endif;?>