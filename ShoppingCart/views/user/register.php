<?php /** @var DH\ShoppingCart\Models\ViewModels\User\RegisterUser $model */ ?>
<h1>
    Register
</h1>
<?php
    if($this->model->success) {
        echo '<div  class="success">You was register successfully.</div>';
    } else if(count($this->model->errors) > 0){
        echo '<div  class="errors">';
        foreach($this->model->errors as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
    }

?>

<div class="register">
    <?= \DH\Mvc\View::registerForm(); ?>
</div>