<?php

namespace frontend\widgets\Blog;

use shop\entities\Blog\post\Comment;

class CommentView
{
    public $comment;

    /**
        * @var self[]
     */
    public $children;

    public function __construct(Comment $comment, $children)
    {
        $this->comment = $comment;
        $this->children = $children;
    }
}