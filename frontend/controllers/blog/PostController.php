<?php

namespace  frontend\controllers\blog;

use shop\readModels\Blog\CategoryReadRepository;
use shop\readModels\Blog\PostReadRepository;
use shop\readModels\Blog\TagReadRepository;
use shop\services\manage\Blog\CommentManageService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PostController extends Controller
{
    public $layout = 'blog';

    private $service;
    private $posts;
    private $categories;
    private $tags;

    public function __construct(
        $id,
        $module,
        CommentManageService $service,
        PostReadRepository $posts,
        CategoryReadRepository $categories,
        TagReadRepository $tags,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->posts = $posts;
        $this->categories = $categories;
        $this->tags = $tags;
    }

    public function actionIndex()
    {
        $dataProvider = $this->posts->getAll();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCategory($slug)
    {
        if (!$category = $this->categories->findBySlug($slug)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = $this->posts->getAllByCategory($category);
        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionTag($slug)
    {
        if (!$tag = $this->tags->findBySlug($slug)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = $this->posts->getAllByTag($tag);
        return $this->render('tag', [
            'tag' => $tag,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPost($id)
    {
        if (!$post = $this->posts->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $this->render('post', [
            'post' => $post,
        ]);
    }

}