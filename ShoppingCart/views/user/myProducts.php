<?php if(count($this->model->products) > 0): ?>
    <table>
        <thead>
        <tr>
            <td>Product name</td>
            <td>Seller</td>
            <td>Quantity</td>
            <td>Price</td>
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
                <?php if($product['status'] == 2) : ?>
                <td><a href="/users/publishItem/<?= $product['user_product_id']; ?>">Publish</a></td>
                <?php elseif($product['status'] == 1) : ?>
                    <td><a href="/users/unpublishItem/<?= $product['user_product_id']; ?>">Unpublish</a></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No products</p>
<?php endif;?>