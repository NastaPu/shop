<?php

namespace frontend\widgets\Blog;

use kartik\base\Widget;
use shop\entities\Blog\Post;
use shop\entities\Blog\post\Comment;
use shop\forms\manage\Blog\CommentForm;
use yii\base\InvalidConfigException;

class CommentWidget extends Widget
{
    /**
     * @var Post
     */
    public $post;

    public function init():void
    {
        if(!$this->post) {
            throw new InvalidConfigException('Specify the post');
        }
    }

    public function run()
    {
        $form = new CommentForm();
        $comments = $this->post->getComments()
            ->orderBy(['parent_id' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
        $items = $this->treeRecursive($comments, null);

        return $this->render('comments/comments', [
            'post' => $this->post,
            'items' => $items,
            'commentForm' => $form,
        ]);
    }

    /**
     *  @param Comment[] $comments
     *  @return CommentView[]
     */
    public function treeRecursive(&$comments, $parentId):array
    {
        $items = [];
        foreach ($comments as $comment) {
            if($comment->parent_id == $parentId) {
                $items[] = new CommentView($comment, $this->treeRecursive($comments, $comment->id));
            }
        }
        return $items;
    }
}