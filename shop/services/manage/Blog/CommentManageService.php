<?php

namespace shop\services\manage\Blog;

use shop\entities\Blog\post\Comment;
use shop\forms\manage\Blog\CommentForm;
use shop\repositories\Blog\PostRepository;
use shop\repositories\UserRepository;
use Yii;

class CommentManageService
{
    private $posts;
    private $users;

    public function __construct(PostRepository $posts, UserRepository $users)
    {
        $this->posts = $posts;
        $this->users = $users;
    }

    public function create($postId, $userId, CommentForm $form):Comment
    {
        $post = $this->posts->get($postId);
        $user = $this->users->get($userId);

        $comment = $post->addComment($user->id, $form->parentId, $form->text);

        $this->posts->save($post);
        return $comment;
    }
}