<?php

namespace shop\forms\manage\Shop;

use shop\entities\Shop\Category;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\validators\SlugValidate;

/**
 * @property MetaForm $meta;
 */
class CategoryForm extends CompositeForm
{
    public $name;
    public $slug;
    public $title;
    public $description;
    public $parentId;
    private $_category;

    public function __construct(Category $category = null, $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->meta = new MetaForm($category->meta);
            $this->_category = $category;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['parentId'], 'integer'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['slug', SlugValidate::class],
            [['name', 'slug'], 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]
        ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}