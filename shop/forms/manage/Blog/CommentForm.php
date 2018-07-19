<?php

namespace shop\forms\manage\Blog;

use yii\base\Model;

class CommentForm extends  Model
{
    public $parentId;
    public $text;

    public function rules()
    {
        return [
            [['text'], 'required'],
            ['text', 'string'],
            ['parentId', 'integer'],
        ];
    }
}