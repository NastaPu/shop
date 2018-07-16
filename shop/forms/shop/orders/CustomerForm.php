<?php

namespace  shop\forms\shop\orders;

use yii\base\Model;

class CustomerForm extends Model
{
    public $name;
    public $phone;

    public function rules(): array
    {
        return [
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
        ];
    }
}