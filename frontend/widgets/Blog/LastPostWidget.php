<?php

namespace frontend\widgets\Blog;

use shop\readModels\Blog\PostReadRepository;
use yii\base\Widget;

class LastPostWidget extends Widget
{
    public $limit;

    private $repository;

    public function __construct(PostReadRepository $repository, $config = [])
    {
        $this->repository =  $repository;
        parent::__construct($config);
    }

    public function run()
    {
        return $this->render('last-post', [
             'posts' => $this->repository->getLast($this->limit)
        ]);
    }
}