<?php

namespace shop\services\manage\Shop;

use shop\dispatcher\EventDispatcher;
use shop\entities\Shop\Category;
use shop\entities\Shop\events\ProductAppearedInStock;
use shop\entities\Shop\Meta;
use shop\entities\Shop\Product;
use shop\entities\Shop\Tag;
use shop\forms\manage\Shop\Product\ModificationForm;
use shop\forms\manage\Shop\Product\PhotoForm;
use shop\forms\manage\Shop\Product\PriceForm;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use shop\forms\manage\Shop\QuantityForm;
use shop\repositories\BrandRepository;
use shop\repositories\CategoryRepository;
use shop\repositories\ProductRepository;
use shop\repositories\TagRepository;
use shop\services\manage\TransactionManager;

class ProductManageService
{
    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $transaction;
    private $dispatcher;

    public function __construct(
        ProductRepository $products,
        BrandRepository $brands,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction,
        EventDispatcher $dispatcher
    ) {
        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->transaction = $transaction;
        $this->dispatcher = $dispatcher;
    }

    public function create(ProductCreateForm $form): Product
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product = Product::create(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            $form->quantity->quantity,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $product->setPrice($form->price->new, $form->price->old);

        foreach($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        foreach ($form->values as $value) {
            $product->setValue($value->id, $value->value);
        }

        foreach ($form->photos->files as $file) {
            $product->addPhoto($file);
        }

        if($form->tags->existing) {
            foreach ($form->tags->existing as $tagId) {
                $tag = $this->tags->get($tagId);
                $product->assignTag($tag->id);
            }
        }

        $this->transaction->wrap(function () use ($product, $form) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);
                    $this->tags->save($tag);
                }
                $product->assignTag($tag->id);
            }
            $this->products->save($product);
        });

        return $product;
    }

    public function edit($id, ProductEditForm $form): void
    {
        $product = $this->products->get($id);
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);
        $product->edit(
            $brand->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->changeMainCategory($category->id);

        $this->transaction->wrap(function () use ($product, $form, $category) {
            $this->assertIsNotRoot($category);
            $product->revokeCategories();
            $product->revokeTags();

            $this->products->save($product);

            foreach ($form->categories->others as $otherId) {
                $category = $this->categories->get($otherId);
                $product->assignCategory($category->id);
            }
            foreach ($form->values as $value) {
                $product->setValue($value->id, $value->value);
            }
            if($form->tags->existing) {
                foreach ($form->tags->existing as $tagId) {
                    $tag = $this->tags->get($tagId);
                    $product->assignTag($tag->id);
                }
            }

            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);
                    $this->tags->save($tag);
                }
                $product->assignTag($tag->id);
            }
            $this->products->save($product);
        });
    }

    public function changeQuantity($id, QuantityForm $form)
    {
        $product = $this->products->get($id);
        $product->changeQuantity($form->quantity);
        $this->products->save($product);

    }

    //category

    private function assertIsNotRoot(Category $category): void
    {
        if ($category->isRoot()) {
            throw new \DomainException('Unable to manage the root category');
        }
    }

    //status

    public function active($id)
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    public function draft($id)
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }

    //price

    public function changePrice($id, PriceForm $form): void
    {
        $product = $this->products->get($id);
        $product->setPrice($form->new, $form->old);
        $this->products->save($product);
        $this->dispatcher->dispatch(new ProductAppearedInStock($product));
    }

    //photo

    public function addPhotos($id, PhotoForm $form): void
    {
        $product = $this->products->get($id);
        foreach ($form->files as $file) {
            $product->addPhoto($file);
        }
        $this->products->save($product);
    }
    public function movePhotoUp($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoUp($photoId);
        $this->products->save($product);
    }
    public function movePhotoDown($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoDown($photoId);
        $this->products->save($product);
    }
    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }

    //related product

    public function addRelatedProduct($id, $otherId): void
    {
        $product = $this->products->get($id);
        $other = $this->products->get($otherId);
        $product->assignRelatedProduct($other->id);
        $this->products->save($product);
    }
    public function removeRelatedProduct($id, $otherId): void
    {
        $product = $this->products->get($id);
        $other = $this->products->get($otherId);
        $product->revokeRelatedProduct($other->id);
        $this->products->save($product);
    }

    //modification

    public function addModification($id, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->addModification(
            $form->code,
            $form->name,
            $form->price,
            $form->quantity
        );
        $this->products->save($product);
    }

    public function editModification($id, $modificationId, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->editModification(
            $modificationId,
            $form->code,
            $form->name,
            $form->price,
            $form->quantity
        );
        $this->products->save($product);
    }

    public function removeModification($id, $modificationId): void
    {
        $product = $this->products->get($id);
        $product->removeModification($modificationId);
        $this->products->save($product);
    }
    
    //remove product

    public function remove($id): void
    {
        $product = $this->products->get($id);
        $this->products->remove($product);
    }

}