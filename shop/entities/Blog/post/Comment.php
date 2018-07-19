<?php

namespace shop\entities\Blog\post;

use shop\entities\Blog\Post;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "blog_comments".
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property int $parent_id
 * @property string $created_at
 * @property string $text
 * @property int $active
 *
 */
class Comment extends ActiveRecord
{
    public static function create($userId, $parentId, $text): self
    {
        $review = new static();
        $review->user_id = $userId;
        $review->parent_id = $parentId;
        $review->text = $text;
        $review->created_at = time();
        $review->active = true;
        return $review;
    }

    public function edit($parentId, $text)
    {
        $this->parent_id = $parentId;
        $this->text = $text;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function draft(): void
    {
        $this->active = false;
    }

    public function isActive(): bool
    {
        return $this->active == true;
    }

    public function isIdEqualTo($id):bool
    {
        return $this->id == $id;
    }

    public function isChildOf($id): bool
    {
        return $this->parent_id == $id;
    }

    public static function tableName():string
    {
        return '{{%blog_comments}}';
    }

    public function getPost():ActiveQuery
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

}
