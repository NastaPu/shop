<?php

namespace  shop\forms\manage\Order;

use shop\entities\Shop\orders\Order;
use yii\base\Model;

class CustomerForm extends Model
{
    public $name;
    public $phone;

    public function __construct(Order $order, $config = [])
    {
        $this->name = $order->customerData->name;
        $this->phone = $order->customerData->phone;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
        ];
    }
}