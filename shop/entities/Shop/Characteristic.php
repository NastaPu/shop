<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "shop_characteristic".
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $required
 * @property string $default
 * @property array $variants_json
 * @property int $sort
 */
class Characteristic extends ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';

    public $variants;

    public static function create($name, $type, $required, $default, array $variants, $sort):self
    {
        $characteristic = new static();
        $characteristic->name = $name;
        $characteristic->type = $type;
        $characteristic->required = $required;
        $characteristic->default = $default;
        $characteristic->variants = $variants;
        $characteristic->sort = $sort;
        return $characteristic;
    }

    public function edit($name, $type, $required, $default, array $variants, $sort):void
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
        $this->variants = $variants;
        $this->sort = $sort;
    }

    public  function isString()
    {
       return $this->type == self::TYPE_STRING;
    }

    public  function isInteger()
    {
        return $this->type == self::TYPE_INTEGER;
    }

    public  function isFloat()
    {
        return $this->type == self::TYPE_FLOAT;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }

    public static function tableName()
    {
        return '{{%shop_characteristic}}';
    }

    public function afterFind(): void
    {
        $this->variants = Json::decode($this->getAttribute('variants_json'));
        parent::afterFind();
    }
    public function beforeSave($insert): bool
    {
        $this->setAttribute('variants_json', Json::encode($this->variants));
        return parent::beforeSave($insert);
    }


}