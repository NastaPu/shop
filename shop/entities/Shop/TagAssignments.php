<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_tag_assignments".
 *
 * @property int $product_id
 * @property int $tag_id
 *
 * @property Product $product
 * @property Tag $tag
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
        return '{{%shop_tag_assignments}}';
    }

}
