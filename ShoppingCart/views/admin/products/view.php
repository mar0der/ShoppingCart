<?php /** @var  DH\ShoppingCart\models\ViewModels\Admin\Products\ViewProducts $model */ ?>
<h1>Admin Products</h1>
<div class="categories">
    <div class="leftSideBar">
        <ul>
            <li><a href="/admin/products/add/">Add Product</a></li>
            <li><a href="/admin/products/view/">All Product</a></li>
        </ul>
    </div>
    <div class="main">
        <?php if(count($this->model->products) > 0): ?>
            <table>
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Name</td>
                    <td>Category</td>
                    <td>Actions</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($this->model->products AS $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['category'] ?></td>
                        <td><a href="/admin/products/addtomarket/<?= $product['id'] ?>">Add To Market</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No products</p>
        <?php endif;?>
    </div>
</div>

