<?php

namespace shop\forms\manage\Blog\Post;

use shop\entities\Blog\Tag;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use shop\entities\Blog\Post;

/**
 * @property array $newNames
 */
class TagsForm extends Model
{
    public $existing = [];
    public $textNew;

    public function __construct(Post $post = null, $config = [])
    {
        if($post) {
            $this->existing = ArrayHelper::getColumn($post->tagAssignments, 'tag_id');
        }
        parent::__construct($config);

    }

    public function rules():array
    {
        return [
            ['existing', 'each', 'rule' => ['integer']],
            ['textNew', 'string',]
        ];
    }

    public function getNewNames(): array
    {
        return array_filter(array_map('trim', preg_split('#\s*,\s*#i', $this->textNew)));
    }

    public function tagsList(): array
    {
        return ArrayHelper::map(Tag::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }
}