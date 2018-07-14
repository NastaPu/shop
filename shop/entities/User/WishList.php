<?php

namespace shop\entities\User;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_wish_list".
 *
 * @property int $user_id
 * @property int $product_id
 */
class WishList extends ActiveRecord
{
    public static function create($productId)
    {
        $list = new static();
        $list->product_id = $productId;
        return $list;
    }

    public function isForProduct($productId):bool
    {
        return $this->product_id == $productId;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_wish_list';
    }

}
