<?php

namespace shop\cart;

use shop\cart\cost\calculator\CalculatorInterface;
use shop\cart\storage\StorageInterface;

class Cart
{
    /**
     * @var CartItem[]
     */
    private $items;
    private $storage;
    private $calculator;

    public function __construct(StorageInterface $storage, CalculatorInterface $calculator)
    {
        $this->storage = $storage;
        $this->calculator = $calculator;
    }

    public function add(CartItem $item):void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if($current->getId() == $item->getId()) {
                $this->items[$i] = $current->plus($item->getQuantity());
                $this->saveItems();
                return;
            }
        }
        $this->items[] = $item;
        $this->saveItems();
    }

    public function set($id, $quantity)
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if($current->getId() == $id) {
                $this->items[$i] = $current->changeQuantity($quantity);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Product does not exist');
    }

    public function remove($id):void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if($current->getId() == $id) {
                unset( $this->items[$i]);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Product does not exist');
    }

    public function clear():void
    {
        $this->items = [];
        $this->saveItems();
    }

    private function loadItems():void
    {
        if($this->items === null) {
            $this->items = $this->storage->load();
                //Yii::$app->session->get('cart', []);
        }
    }

    private function saveItems():void
    {
        //Yii::$app->session->set('cart', $this->items);
        $this->storage->save($this->items);
    }

    /**
     * @return CartItem[]
     */
    public function getItems():array
    {
        $this->loadItems();
        return $this->items;
    }

    public function getAmount():int
    {
        $this->loadItems();
        return count($this->items);
    }

    public function getCost()
    {
        $this->loadItems();
        return $this->calculator->getCost($this->items);
    }

}