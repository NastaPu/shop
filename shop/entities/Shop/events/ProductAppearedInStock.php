<?php

namespace shop\entities\Shop\events;

use shop\entities\Shop\Product;

class ProductAppearedInStock
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}