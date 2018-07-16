<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $method shop\entities\Shop\DeliveryMethod */
/* @var $model shop\forms\manage\Shop\DeliveryMethodForm */

$this->title = 'Update Delivery Method: ' . $method->name;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $method->name, 'url' => ['view', 'id' => $method->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="delivery-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
