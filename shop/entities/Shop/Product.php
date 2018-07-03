<?php

namespace shop\entities\Shop;

use shop\entities\behavior\MetaBehavior;
use yii\db\ActiveRecord;
use shop\entities\Shop\Meta;
use shop\entities\Shop\Brand;
use shop\entities\Shop\Category;
use yii\db\ActiveQuery;
use shop\entities\Shop\CategoryAssignment;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * This is the model class for table "shop_product".
 *
 * @property int $id
 * @property int $category_id
 * @property int $brand_id
 * @property string $created_at
 * @property string $code
 * @property string $name
 * @property int $price_old
 * @property int $price_new
 * @property string $rating
 * @property string $meta_json
 *
 * @property Meta $meta
 * @property Brand $brand
 * @property Category $category
 * @property CategoryAssignment[] $categoryAssignments
 * @property Value[] $values
 */
class Product extends ActiveRecord
{
    public $meta;

    public static function create($brandId, $categoryId, $code, $name, Meta $meta): self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->meta = $meta;
        $product->created_at = time();
        return $product;
    }

    public function setValue($id, $value):void
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                $val->change($value);
                $this->values = $values;
                return;
            }
        }
        $values[] = Value::create($id, $value);
        $this->values = $values;

    }

    public function getVal($id): Value
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                return $val;
            }
        }
        return Value::blank($id);
    }

    public function setPrice($new, $old): void
    {
        $this->price_new = $new;
        $this->price_old = $old;
    }

    public function changeMainCategory($categoryId):void
    {
        $this->category_id = $categoryId;
    }

    public function assignCategory($id):void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $assignment) {
            if($assignment->isForCategory($id)) {
                return;
            }
            $assignments[] = CategoryAssignment::create($id);
            $this->categoryAssignments = $assignments;
        }
    }

    public function revokeCategory($id):void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForCategory($id)) {
                unset($assignments[$i]);
                $this->categoryAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found');
    }

    public function revokeCategories(): void
    {
        $this->categoryAssignments = [];
    }

    public static function tableName() :string
    {
        return 'shop_product';
    }

    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryAssignment(): ActiveQuery
    {
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    public function getValue() :ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['categoryAssignment', 'value'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}
