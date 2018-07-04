<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;
use shop\entities\behavior\MetaBehavior;

/**
 * This is the model class for table "shop_brand".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Meta $meta
 */
class Brand extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, Meta $meta): self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->meta = $meta;
        return $brand;
    }

    public function edit($name, $slug, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->meta = $meta;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_brand}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::className(),
        ];
    }
}
