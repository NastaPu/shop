<?php

namespace shop\entities\Blog;

use shop\entities\behavior\MetaBehavior;
use shop\entities\Shop\Meta;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "blog_categories".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property array $meta_json
 * @property integer $sort
 */
class Category extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $title, $description, $sort, Meta $meta): self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;
        $category->sort =$sort;
        $category->meta = $meta;
        return $category;
    }

    public function edit($name, $slug, $title, $description, $sort, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->sort = $sort;
        $this->meta = $meta;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%blog_categories}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::className(),
        ];
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->getHeadingTile();
    }

    public function getHeadingTile(): string
    {
        return $this->title ?: $this->name;
    }

}
