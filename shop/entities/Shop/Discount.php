<?php

namespace shop\entities\Shop;

use shop\entities\Shop\queries\DiscountQuery;
use yii\db\ActiveRecord;

/**
 *
 * @property integer $percent
 * @property string $name
 * @property string $from_date
 * @property string $to_date
 * @property bool $active
 * @property integer $sort
 */
class Discount extends ActiveRecord
{
    public static function create($percent, $name, $fromDate, $toDate, $sort):self
    {
        $discount = new static();
        $discount->percent = $percent;
        $discount->name = $name;
        $discount->from_date = $fromDate;
        $discount->to_date = $toDate;
        $discount->active = true;
        $discount->sort = $sort;
        return $discount;

    }

    public function edit($percent, $name, $fromDate, $toDate, $sort):void
    {
        $this->percent = $percent;
        $this->name = $name;
        $this->from_date = $fromDate;
        $this->to_date = $toDate;
        $this->sort = $sort;
    }

    public function isEnabled():bool
    {
        return true;
    }

    public function activate()
    {
        $this->active = true;
    }

    public function draft()
    {
        $this->active = false;
    }

    public static function find(): DiscountQuery
    {
        return new DiscountQuery(static::class);
    }

    public static function tableName()
    {
        return '{{%shop_discount}}';
    }

}