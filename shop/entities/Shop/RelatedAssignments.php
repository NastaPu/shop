<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_related_assignments".
 *
 * @property int $product_id
 * @property int $related_id
 *
 * @property Product $product
 * @property Product $related
 */
class RelatedAssignments extends ActiveRecord
{
    public static function create($productId): self
    {
        $assignment = new static();
        $assignment->related_id = $productId;
        return $assignment;
    }

    public function isForProduct($id): bool
    {
        return $this->related_id == $id;
    }

    public static function tableName():string
    {
        return '{{%shop_related_assignments}}';
    }
}
