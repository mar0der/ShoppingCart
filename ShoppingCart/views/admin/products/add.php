<?php /** @var  DH\ShoppingCart\Models\ViewModels\Admin\Products\AddProduct $model */ ?>
<h1>Admin Products</h1>
<div class="categories">
    <div class="leftSideBar">
        <ul>
            <li><a href="/admin/products/add/">Add Product</a></li>
            <li><a href="/admin/products/view/">All Product</a></li>
        </ul>
    </div>
    <div class="main">
        <?= \DH\Mvc\View::addProductsForm() ?>

    </div>
</div>
