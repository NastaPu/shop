<?php

namespace shop\entities\Blog\queries;

use shop\entities\Blog\Post;
use yii\db\ActiveQuery;

class PostQuery extends ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([$alias ? $alias . '.' : ''. 'status' => Post::STATUS_ACTIVE,
        ]);
    }
}