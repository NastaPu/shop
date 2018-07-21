<?php

namespace shop\services\manage\Blog\post;

use shop\forms\manage\Blog\Post\CommentEditForm;
use shop\repositories\Blog\PostRepository;

class CommentManageService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function edit($postId, $id, CommentEditForm $form)
    {
        $post = $this->posts->get($postId);
        $post->editComment($post->id, $form->parentId, $form->text);
        $this->posts->save($post);
    }

    public function activate($postId, $id)
    {
        $post = $this->posts->get($postId);
        $post->activateComment($id);
        $this->posts->save($post);
    }

    public function remove($postId, $id)
    {
        $post = $this->posts->get($postId);
        $post->removeComment($id);
        $this->posts->save($post);
    }
}