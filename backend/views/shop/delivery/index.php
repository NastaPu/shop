<?php

use shop\entities\Shop\DeliveryMethod;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\DeliveryMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Methods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-method-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Delivery Method', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'value' => function (DeliveryMethod $model) {
                    return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                    },
                'format' => 'raw',
            ],
            'cost',
            'min_weight',
            'max_weight',
            ['class' => ActionColumn::class],
        ],
    ]); ?>
</div>
