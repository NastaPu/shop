<?php

use yii\helpers\Html;
use yii\grid\GridView;
use shop\entities\Shop\Characteristic;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\CharacteristicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Characteristics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Characteristic', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'name',
                        'value' => function (Characteristic $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type',
                        'filter' => $searchModel->typesList(),
                        'value' => function (Characteristic $model) {
                            return \shop\helpers\CharacteristicHelper::typeName($model->type);
                        },
                    ],
                    [
                        'attribute' => 'required',
                        'filter' => $searchModel->requiredList(),
                        'format' => 'boolean',
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
