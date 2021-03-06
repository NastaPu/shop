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
                <a href="<?= Html::encode(Url::to(['/site/request-password-reset'])) ?>" class="list-group-item">Forgotten Password</a>
                <a href=<?= Html::encode(Url::to(['/cabinet/default/index'])) ?> class="list-group-item">My Account</a>
                <a href=<?= Html::encode(Url::to(['/cabinet/wishlist/index'])) ?> class="list-group-item">Wish List</a>
                <a href=<?= Html::encode(Url::to(['/cabinet/order/index'])) ?> class="list-group-item">Order History</a>
            </div>
        </aside>
    </div>

<?php $this->endContent() ?>