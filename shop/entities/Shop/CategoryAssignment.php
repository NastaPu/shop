<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_category_assignment".
 *
 * @property int $product_id
 * @property int $category_id
 *
 * @property Category $category
 * @property Product $product
 */
class CategoryAssignment extends ActiveRecord
{
    public static function create($categoryId): self
    {
        $assignment = new static();
        $assignment->category_id = $categoryId;
        return $assignment;
    }

    public function isForCategory($id): bool
    {
        return $this->category_id == $id;
    }

    public static function tableName():string
    {
        return '{{%shop_category_assignment}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getCategory()
    {
        return $this->hasOne(ShopCategory::className(), ['id' => 'category_id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
   /* public function getProduct()
    {
        return $this->hasOne(ShopProduct::className(), ['id' => 'product_id']);
    }*/
}
