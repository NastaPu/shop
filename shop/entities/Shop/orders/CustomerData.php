<?php

namespace shop\entities\Shop\orders;

class CustomerData
{
    public $name;
    public $phone;

    public function __construct($name, $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
    }
}