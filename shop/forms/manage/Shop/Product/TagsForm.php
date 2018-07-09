<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Tag;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use shop\entities\Shop\Product;

/**
 * @property array $newNames
 */
class TagsForm extends Model
{
    public $existing = [];
    public $textNew;

    public function __construct(Product $product = null, $config = [])
    {
        if($product) {
            $this->existing = ArrayHelper::getColumn($product->tagAssignments, 'tag_id');
        }
        parent::__construct($config);

    }

    public function rules():array
    {
        return [
            ['existing', 'required'],
            ['existing', 'each', 'rule' => ['integer']],
            ['textNew', 'string',]
        ];
    }

    public function getNewNames(): array
    {
        return array_filter(array_map('trim', preg_split('#\s*,\s*#i', $this->textNew)));
    }

    public function tagList(): array
    {
        return ArrayHelper::map(Tag::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }
}