<?php

namespace shop\entities\Shop\orders;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\Shop\DeliveryMethod;
use shop\entities\Shop\Order\Status;
use shop\entities\User\User;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Json;

/**
 * This is the model class for table "shop_orders".
 *
 * @property int $id
 * @property int $created_at
 * @property int $user_id
 * @property int $delivery_method_id
 * @property string $delivery_method_name
 * @property int $delivery_cost
 * @property string $payment_method
 * @property int $cost
 * @property string $note
 * @property int $current_status
 * @property string $cancel_reason
 * @property array $statuses_json
 * @property string $customer_phone
 * @property string $customer_name
 * @property string $delivery_index
 * @property string $delivery_address
 *
 * @property CustomerData $customerData
 * @property DeliveryData $deliveryData
 *
 *  @property OrderItem[] $items
 * @property Status[] $statuses
 */
class Order extends ActiveRecord
{
    public $customerData;
    public $deliveryData;
    public $statuses = [];

    public static function create($userId, CustomerData $customerData, array $items, $cost, $note): self
    {
        $order = new static();
        $order->user_id = $userId;
        $order->customerData = $customerData;
        $order->items = $items;
        $order->cost = $cost;
        $order->note = $note;
        $order->created_at = time();
        $order->addStatus(Status::NEW);
        return $order;
    }

    public function edit(CustomerData $customerData, $note): void
    {
        $this->customerData = $customerData;
        $this->note = $note;
    }

    public function setDeliveryInfo(DeliveryMethod $method, DeliveryData $deliveryData): void
    {
        $this->delivery_method_id = $method->id;
        $this->delivery_method_name = $method->cost;
        $this->delivery_cost = $method->cost;
        $this->deliveryData = $deliveryData;
    }

    private function addStatus($value) :void
    {
        $this->statuses[] = new Status($value, time());
        $this->current_status = $value;
    }

    public function pay($method): void
    {
        if ($this->isPaid()) {
            throw new \DomainException('Order is already paid');
        }
        $this->payment_method = $method;
        $this->addStatus(Status::PAID);
    }

    public function send(): void
    {
        if ($this->isSent()) {
            throw new \DomainException('Order is already sent');
        }
        $this->addStatus(Status::SENT);
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new \DomainException('Order is already completed');
        }
        $this->addStatus(Status::COMPLETED);
    }

    public function cancel($reason): void
    {
        if ($this->isCancelled()) {
            throw new \DomainException('Order is already cancelled');
        }
        $this->cancel_reason = $reason;
        $this->addStatus(Status::CANCELLED);
    }

    public function getTotalCost(): int
    {
        return $this->cost + $this->delivery_cost;
    }

    public function isPaid(): bool
    {
        return $this->current_status == Status::PAID;
    }

    public function isSent(): bool
    {
        return $this->current_status == Status::SENT;
    }

    public function isCompleted(): bool
    {
        return $this->current_status == Status::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->current_status == Status::CANCELLED;
    }

    public static function tableName():string
    {
        return '{{%shop_orders}}';
    }

    public function getDeliveryMethod():ActiveQuery
    {
        return $this->hasOne(DeliveryMethod::className(), ['id' => 'delivery_method_id']);
    }

    public function getUser():ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getItems():ActiveQuery
    {
        return $this->hasMany(OrderItem::className(),['order_id' => 'id']);
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['items'],
            ],
        ];
    }
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function afterFind():void
    {
        $this->statuses = array_map(function ($row) {
           return new Status(
               $row['value'],
               $row['created_at']
           );
        }, Json::decode($this->getAttribute('status-json')));

        $this->customerData = new CustomerData(
            $this->getAttribute('customer_phone'),
            $this->getAttribute('customer_name')
        );

        $this->deliveryData = new DeliveryData(
            $this->getAttribute('delivery_index'),
            $this->getAttribute('delivery_address')
        );

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->setAttribute('statuses_json', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->value,
                'created_at' => $status->created_at,
            ];
        }, $this->statuses)));

        $this->setAttribute('customer_phone', $this->customerData->phone);
        $this->setAttribute('customer_name', $this->customerData->name);
        $this->setAttribute('delivery_index', $this->deliveryData->index);
        $this->setAttribute('delivery_address', $this->deliveryData->address);

        return parent::beforeSave($insert);
    }
}
