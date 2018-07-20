<?php

namespace shop\entities\Blog;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\behavior\MetaBehavior;
use shop\entities\Blog\post\Comment;
use shop\entities\Blog\post\TagAssignments;
use shop\entities\Blog\queries\PostQuery;
use shop\entities\Shop\Meta;
use shop\services\WaterMarker;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * This is the model class for table "blog_posts".
 *
 * @property int $id
 * @property int $category_id
 * @property string $created_at
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $photo
 * @property int $status
 * @property int $comments_count
 * @property array $meta_json
 *
 * @property Meta $meta
 * @property Category $category
 * @property TagAssignments[] $tagAssignments
 * @property Tag[] $tags
 * @property Comment[] $comments
 *
 *  @mixin ImageUploadBehavior
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public $meta;

    public static function create($categoryId, $title, $description, $content, Meta $meta) :self
    {
        $post = new static();
        $post->category_id = $categoryId;
        $post->title = $title;
        $post->description = $description;
        $post->content = $content;
        $post->meta = $meta;
        $post->status = self::STATUS_DRAFT;
        $post->created_at = time();
        $post->comments_count = 0;
        return $post;
    }

    public function edit($categoryId, $title, $description, $content, Meta $meta) :void
    {
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function setPhoto(UploadedFile $photo)
    {
        $this->photo = $photo;
    }

    public function activate()
    {
        if($this->isActive()) {
            throw  new \DomainException('Post is already active');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft()
    {
        if($this->isDraft()) {
            throw  new \DomainException('Post is already draft');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive():bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft():bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->title;
    }

    //comment

    public function addComment($userId, $parentId, $text):Comment
    {
        $parent = $parentId ? $this->getComment($parentId) : null;
        if(Yii::$app->user->id != $userId) {
            throw new \DomainException('Comments  can only authorized users');
        }
        if($parent && !$parent->isActive()) {
            throw new \DomainException('Cannot add comment to inactive parent');
        }
        $comments = $this->comments;
        $comment = Comment::create($userId, $parent ? $parent->id : null, $text);
        $comments[] = $comment;
        $this->updateComments($comments);
        return $comment;
    }

    public function activateComment($id):void
    {
        $comments = $this->comments;
        foreach ($comments as $comment) {
            if($comment->isIdEqualTo($id)) {
                $comment->activate();
                $this->updateComments($comments);
                return;
            }
        }
        throw new \DomainException('Comment is not found');
    }

    public function removeComment($id)
    {
        $comments = $this->comments;
        foreach ($comments as $i => $comment) {
            if($comment->isIdEqualTo($id)) {
                if($this->hasChildren($comment->id)) {
                    $comment->draft();
                } else {
                    unset($comments[$i]);
                }
                $this->updateComments($comments);
                return;
            }
        }
        throw new \DomainException('Comment is not found');
    }

    public function updateComments(array $comments):void
    {
        $this->comments = $comments;
        $this->comments_count = count(array_filter($comments, function (Comment $comment) {
            return $comment->isActive();
        }));
    }

    public function getComment($id):Comment
    {
        $comments = $this->comments;
        foreach ($comments as $comment) {
            if($comment->isIdEqualTo($id)) {
                return $comment;
            }
        }
        throw new \DomainException('Comment is not found');
    }

    public function hasChildren($id):bool
    {
        $comments = $this->comments;
        foreach ($comments as $comment) {
            if($comment->isChildOf($id)) {
                return true;
            }
        }
        return false;
    }

    //tag

    public function assignTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }
        $assignments[] = TagAssignments::create($id);
        $this->tagAssignments = $assignments;
    }
    public function revokeTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForTag($id)) {
                unset($assignments[$i]);
                $this->tagAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }
    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    public function getCategory():ActiveQuery
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignments::class, ['post_id' => 'id']);
    }

    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    public static function tableName():string
    {
        return '{{%blog_posts}}';
    }
    public function behaviors(): array
    {
        return [
            MetaBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['tagAssignments', 'comments'],
            ],
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'photo',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/posts/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/posts/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/posts/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/posts/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'blog_list' => ['width' => 1000, 'height' => 150],
                    'origin' => ['processor' => [new WaterMarker(1024, 768, '@frontend/web/image/logo.png'), 'process']],
                ],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): PostQuery
    {
        return new PostQuery(static::class);
    }

}
