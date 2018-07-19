<?php

namespace shop\entities\Blog\post;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "blog_tag_assignments".
 *
 * @property int $tag_id
 * @property int $post_id

 */
class TagAssignments extends ActiveRecord
{
    public static function create($tagId): self
    {
        $assignment = new static();
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    public function isForTag($id): bool
    {
        return $this->tag_id == $id;
    }

    public static function tableName():string
    {
        return '{{%blog_tag_assignments}}';
    }
}
