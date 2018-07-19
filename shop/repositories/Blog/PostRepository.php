<?php

namespace shop\repositories\Blog;

use shop\entities\Blog\Post;

class PostRepository
{
    public function get($id): Post
    {
        if (!$post = Post::findOne($id)) {
            throw new \DomainException('Tag is not found');
        }
        return $post;
    }

    public function save(Post $post): void
    {
        if (!$post->save()) {
            throw new \RuntimeException('Saving error');
        }
    }

    public function existByCategory($id):bool
    {
        return Post::find()->andWhere(['category_id' => $id])->exists();
    }

    public function remove(Post $post): void
    {
        if (!$post->delete()) {
            throw new \RuntimeException('Removing error');
        }
    }
}