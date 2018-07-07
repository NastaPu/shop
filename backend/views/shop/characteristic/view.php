<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $characteristic shop\entities\Shop\Characteristic */

$this->title = $characteristic->name;
$this->params['breadcrumbs'][] = ['label' => 'Characteristics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $characteristic->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $characteristic->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $characteristic,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'type',
                'value' => \shop\helpers\CharacteristicHelper::typeName($characteristic->type),
                ],
            'required',
            'default',
            [
                'attribute' => 'variants',
                'value' => implode(PHP_EOL, $characteristic->variants),
                'format' => 'ntext',
            ],
        ],
    ]) ?>

</div>
