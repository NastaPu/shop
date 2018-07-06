<?php

namespace shop\services\manage\Shop;

use shop\entities\Shop\Meta;
use shop\entities\Shop\Brand;
use shop\forms\manage\Shop\BrandForm;
use shop\repositories\BrandRepository;
use shop\repositories\ProductRepository;

class BrandManageService
{
    private $brands;
    private $product;
    public function __construct(BrandRepository $brands,ProductRepository $product)
    {
        $this->brands = $brands;
        $this->product = $product;
    }
    public function create(BrandForm $form): Brand
    {
        $brand = Brand::create(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->brands->save($brand);
        return $brand;
    }
    public function edit($id, BrandForm $form): void
    {
        $brand = $this->brands->get($id);
        $brand->edit(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->brands->save($brand);
    }
    public function remove($id): void
    {
        $brand = $this->brands->get($id);
        if($this->product->existsByBrand($id)) {
            throw new \DomainException('Can not be deleted a brand with products');
        }
        $this->brands->remove($brand);
    }
}