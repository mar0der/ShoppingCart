<?php /** @var DH\ShoppingCart\Models\ViewModels\Category\CategoryView $model */?>
<h1><?= $this->model->categoryName ?> Products</h1>
<div class="categories">
    <div class="leftSideBar">
        <ul>
            <li><a href="/categories/view/">All</a></li>
            <?php foreach($this->model->categories as $category) : ?>
            <li><a href="/categories/view/<?= $category['name'] ?>"><?= $category['name'] ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="main">
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
                <td><?= $product['username'] ?></td>
                <td><?= $product['quantity'] ?></td>
                <td>$<?= $product['sell_price'] ?></td>
                <?php if(\DH\Mvc\View::logged()) : ?>
                <td class="addToCartColumn"><?=
                    \DH\ShoppingCart\Forms\AddToCartForm::create('/cart/add/', $product['id']) ?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else : ?>
            <p>No products</p>
        <?php endif;?>
    </div>
</div>