<?php /** @var DH\ShoppingCart\Models\ViewModels\User\ProfileUser $model */ ?>
<div>
    Hello, <?= $this->model->username ?> <br/>
    Money: $<?= $this->model->money ?> <br/>
    Email: <?= $this->model->email ?> <br/>
    Role: <?= $this->model->role ?> <br/>
    <?php if (self::role() == 3) : ?>
    Actions: <a href="/admin/users/ban/<?= $this->model->id ?>" >Ban this user</a> <br/>
    <?php endif; ?>
</div>
