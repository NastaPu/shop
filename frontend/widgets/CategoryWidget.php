<?php

namespace frontend\widgets;

use shop\entities\Shop\Category;
use shop\readModels\CategoryReadRepository;
use yii\base\Widget;
use yii\helpers\Html;

class CategoryWidget extends Widget
{
    public $active;

    private $category;

    public function __construct(CategoryReadRepository $category, $config = [])
    {
        parent::__construct($config);
        $this->category = $category;
    }

    public function run()
    {
        return Html::tag('div', implode(PHP_EOL, array_map(function (Category $category){
            $indent = ($category->depth > 1 ? str_repeat('&nbsp&nbsp&nbsp', $category->depth - 1).'-' : '');
            $active = $this->active && ($this->active->id == $category->id || $this->active->isChildOf($category));
            return Html::a(
                    $indent . Html::encode($category->name),
                    ['/shop/catalog/category', 'id' => $category->id],
                    ['class' => $active ? 'list-group-item active' : 'list-group-item']
            );
        },$this->category->getTreeWithSubsOf($this->active))), [
            'class' => 'list-group',
        ]);

    }
}