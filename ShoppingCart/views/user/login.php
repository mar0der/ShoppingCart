<?php /** @var DH\ShoppingCart\Models\ViewModels\User\LoginUser $model */ ?>
<h1>
    Login
</h1>
<?php
     if(count($this->model->errors) > 0) {
         echo '<div  class="errors">';
         foreach ($this->model->errors as $error) {
             echo '<p>' . $error . '</p>';
         }

         echo '</div>';
     }
?>
<div class="login">
    <?= \DH\Mvc\View::loginForm() ?>
</div>