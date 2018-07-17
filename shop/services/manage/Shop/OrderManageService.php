<?php

namespace shop\services\manage\Shop;

use shop\entities\Shop\orders\CustomerData;
use shop\entities\Shop\orders\DeliveryData;
use shop\forms\manage\Order\OrderEditForm;
use shop\repositories\DeliveryMethodRepository;
use shop\repositories\OrderRepository;

class OrderManageService
{
    private $orders;
    private $deliveryMethods;

    public function __construct(OrderRepository $orders, DeliveryMethodRepository $deliveryMethods)
    {
        $this->orders = $orders;
        $this->deliveryMethods = $deliveryMethods;
    }

    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->orders->get($id);
        $order->edit(
            new CustomerData(
                $form->customer->phone,
                $form->customer->name
            ),
            $form->note
        );
        $order->setDeliveryInfo(
            $this->deliveryMethods->get($form->delivery->method),
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );
        $this->orders->save($order);
    }

    public function remove($id): void
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }
}