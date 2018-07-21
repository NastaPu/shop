<?php

namespace shop\entities;

use paulzi\nestedsets\NestedSetsBehavior;
use shop\entities\behavior\MetaBehavior;
use shop\entities\Shop\Meta;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property array $meta_json
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property Meta $meta
 *
 * @property Page $parent
 * @property Page[] $parents
 * @property Page[] $children
 * @property Page $prev
 * @property Page $next
 * @mixin NestedSetsBehavior
 */
class Page extends ActiveRecord
{
    public $meta;

    public static function create($title, $slug, $content, Meta $meta): self
    {
        $category = new static();
        $category->title = $title;
        $category->slug = $slug;
        $category->title = $title;
        $category->content = $content;
        $category->meta = $meta;
        return $category;
    }

    public function edit($title, $slug, $content, Meta $meta): void
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->title;
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::className(),
            NestedSetsBehavior::className(),
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function tableName():string
    {
        return '{{%pages}}';
    }
}
