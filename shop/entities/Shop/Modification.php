<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_modification".
 *
 * @property int $id
 * @property int $product_id
 * @property string $code
 * @property string $name
 * @property int $price
 *
 * @property Product $product
 */
class Modification extends ActiveRecord
{
    public static function create($code, $name, $price): self
    {
        $modification = new static();
        $modification->code = $code;
        $modification->name = $name;
        $modification->price = $price;
        return $modification;
    }

    public function edit($code, $name, $price): void
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    public function isCodeEqualTo($code)
    {
        return $this->code === $code;
    }

    public static function tableName(): string
    {
        return '{{%shop_modification}}';
    }


}