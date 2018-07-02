<?php

namespace shop\entities\Shop;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_tag".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Tag extends ActiveRecord
{
    public static function create($name, $slug):self
    {
        $tag = new static();
        $tag->name = $name;
        $tag->slug =$slug;
        return $tag;
    }

    public function edit($name, $slug):void
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }
}
