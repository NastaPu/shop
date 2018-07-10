<?php

namespace shop\helpers;

use shop\entities\Shop\Product;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ProductHelper
{
    public static function statusList():array
    {
        return[
            Product::STATUS_DRAFT => 'Draft',
            Product::STATUS_ACTIVE => 'Active'
        ];
    }

    public function statusName($status)
    {
         return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status)
    {
        switch($status){
            case Product::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            case Product::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);

    }
}