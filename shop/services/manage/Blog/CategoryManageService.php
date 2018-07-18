<?php

namespace shop\services\manage\Blog;

use shop\entities\Shop\Meta;
use shop\entities\Blog\Category;
use shop\forms\manage\Blog\CategoryForm;
use shop\repositories\Blog\CategoryRepository;
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
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->categories->save($category);
        return $category;
    }

    public function edit($id, CategoryForm $form): void
    {
        $category = $this->categories->get($id);
        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->categories->save($category);

    }

    public function remove($id): void
    {
        $category = $this->categories->get($id);
        $this->categories->remove($category);
    }
}