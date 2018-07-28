<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use frontend\assets\OwlCarouselAsset;
use frontend\widgets\CartWidget;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
OwlCarouselAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="<?= Html::encode(Url::canonical()) ?>" rel="canonical"/>
    <link href="<?= Yii::getAlias('@web/image/cart.png') ?>" rel="icon"/>
    <?php $this->head() ?>
</head>
<body class="common-home">
<?php $this->beginBody() ?>
<nav id="top">
    <div class="container">
        <div id="top-links" class="nav pull-right">
            <ul class="list-inline">
                <li><a href="/index.php?route=information/contact"><i class="fa fa-phone"></i></a>
                    <span class="hidden-xs hidden-sm hidden-md">8-(800)-800-800</span></li>
                <li class="dropdown"><a href="/index.php?route=account/account" title="My Account"
                                        class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span
                                class="hidden-xs hidden-sm hidden-md">My Account</span> <span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <li><a href="<?= Html::encode(Url::to(['/site/login'])) ?>">Login</a></li>
                            <li><a href="<?= Html::encode(Url::to(['/site/signup'])) ?>">Signup</a></li>
                        <?php else: ?>
                            <li><a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>">Cabinet</a></li>
                            <li><a href="<?= Html::encode(Url::to(['/site/logout'])) ?>" data-method="post">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li><a href="<?= Url::to(['/cabinet/wishlist/index']) ?>" id="wishlist-total"
                       title="Wish List"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md">Wish List</span></a>
                </li>
                <li><a href="<?= Url::to(['/shop/cart/index']) ?>"  title="Shopping Cart"><i
                                class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Shopping Cart</span></a>
                </li>
                <li><a href="<?= Url::to(['/shop/checkout/index']) ?>" title="Checkout"><i
                                class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md">Checkout</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div id="logo">
                    <a href="<?= Url::home() ?>"><img
                         src="<?= Yii::getAlias('@web/image/logo.png') ?>" title="Your Store" alt="Your Store"
                         class="img-responsive"/></a>
                </div>
            </div>
            <div class="col-sm-5">
                <?= Html::beginForm(['/shop/catalog/search'], 'get') ?>
                <div id="search" class="input-group">
                    <input type="text" name="text" value="" placeholder="Search" class="form-control input-lg"/>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default btn-lg"><img src="http://static.sh.com/cache/manufacturers/zoom.png" alt="zoom"></i></button>
                    </span>
                </div>
                <?= Html::endForm() ?>
            </div>
            <div class="col-sm-3">
                <?= CartWidget::widget() ?>
            </div>
            </div>
        </div>
    </div>
</header>
<div class="container">
 <?php
    NavBar::begin([
        'options' => [
            'screenReaderToggleText' => 'Menu',
            'id' => 'menu',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'nav navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Catalog', 'url' => ['/shop/catalog/index']],
            ['label' => 'Blog', 'url' => ['/blog/post/index']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
        ],
    ]);
    NavBar::end();
    ?>
</div>
<div class="container">
    <?= Breadcrumbs::widget([
             'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>
</div>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h5>Information</h5>
                <ul class="list-unstyled">
                    <li><a href="http://sh.com/about/repair">Repair</a></li>
                    <li><a href="http://sh.com/about/inspection">Inspection</a></li>

                </ul>
            </div>

            <div class="col-sm-3">
            </div>

            <div class="col-sm-3">
            </div>

            <div class="col-sm-3">
            </div>
        </div>
        <hr>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>