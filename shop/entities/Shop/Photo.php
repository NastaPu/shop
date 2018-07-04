<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "shop_photo".
 *
 * @property int $id
 * @property int $product_id
 * @property string $file
 * @property int $sort
 *
 */
class Photo extends ActiveRecord
{

    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public static function tableName():string
    {
        return 'shop_photo';
    }
}
