<?php

namespace shop\entities\Blog;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\behavior\MetaBehavior;
use shop\entities\Blog\post\TagAssignments;
use shop\entities\Blog\queries\PostQuery;
use shop\entities\Shop\Meta;
use shop\services\WaterMarker;
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
 * @property array $meta_json
 *
 * @property Meta $meta
 * @property Category $category
 * @property TagAssignments[] $tagAssignments
 * @property Tag[] $tags
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
                'relations' => ['tagAssignments'],
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
