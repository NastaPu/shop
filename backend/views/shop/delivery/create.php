<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Shop\DeliveryMethodForm */

$this->title = 'Create Delivery Method';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-method-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
