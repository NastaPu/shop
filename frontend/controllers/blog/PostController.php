<?php

namespace  frontend\controllers\blog;

use shop\forms\manage\Blog\CommentForm;
use shop\readModels\Blog\CategoryReadRepository;
use shop\readModels\Blog\PostReadRepository;
use shop\readModels\Blog\TagReadRepository;
use shop\services\manage\Blog\CommentManageService;
use Yii;
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

    public function actionComment($id)
    {
        if (!$post = $this->posts->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $form = new CommentForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $comment = $this->service->create($post->id, Yii::$app->user->id, $form);
                return $this->redirect(['post', 'id' => $post->id, '#' => 'comment_' . $comment->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('comment', [
            'post' => $post,
            'model' => $form,
        ]);
    }

}