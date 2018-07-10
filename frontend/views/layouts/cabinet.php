<?php
/* @var $this \yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $content string */
?>

<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

    <div class="row">
        <div id="content" class="col-sm-9">
            <?= $content ?>
        </div>
        <aside id="column-right" class="col-sm-3 hidden-xs">
            <div class="list-group">
                <a href="<?= Html::encode(Url::to(['/site/login'])) ?>" class="list-group-item">Login</a>
                <a href="<?= Html::encode(Url::to(['/site/signup'])) ?>" class="list-group-item">Signup</a>
                <a href="<?= Html::encode(Url::to(['/site/request-password-reset'])) ?>" class="list-group-item">Forgotten Password</a>
                <a href="/account/account" class="list-group-item">My Account</a>
                <a href="/account/wishlist" class="list-group-item">Wish List</a>
                <a href="/account/order" class="list-group-item">Order History</a>
                <a href="/account/newsletter" class="list-group-item">Newsletter</a>
            </div>
        </aside>
    </div>

<?php $this->endContent() ?>