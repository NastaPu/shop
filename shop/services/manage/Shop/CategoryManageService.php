<?php

namespace shop\services\manage\Shop;

use shop\entities\Shop\Meta;
use shop\entities\Shop\Category;
use shop\forms\manage\Shop\CategoryForm;
use shop\repositories\CategoryRepository;
use shop\repositories\ProductRepository;

class CategoryManageService
{
    private $categories;
    private $product;

    public function __construct(CategoryRepository $categories, ProductRepository $product)
    {
        $this->categories = $categories;
        $this->product = $product;
    }
    public function create(CategoryForm $form): Category
    {
        $parent = $this->categories->get($form->parentId);
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $category->appendTo($parent);
        $this->categories->save($category);
        return $category;
    }
    public function edit($id, CategoryForm $form): void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        if ($form->parentId !== $category->parent->id) {
            $parent = $this->categories->get($form->parentId);
            $category->appendTo($parent);
        }
        $this->categories->save($category);

    }

    public function moveUp($id):void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if($prev = $category->prev) {
            $category->insertBefore($prev);
        }
        $this->categories->save($category);
    }

    public function moveDown($id):void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if($next = $category->next) {
            $category->insertAfter($next);
        }
        $this->categories->save($category);
    }

    public function remove($id): void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if($this->product->existsByCategory($id)) {
            throw new \DomainException('Can not be deleted a category with products');
        }
        $this->categories->remove($category);
    }
    private function assertIsNotRoot(Category $category): void
    {
        if ($category->isRoot()) {
            throw new \DomainException('Unable to manage the root category');
        }
    }
}