<?php

use yii\helpers\Html;
use yii\grid\GridView;
use shop\entities\Shop\Brand;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Brands';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Brand', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class='box'>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                        'attribute' => 'name',
                        'value' => function (Brand $model) {
                            return Html::a(Html::encode($model->name),['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'slug',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
