<?php

use yii\db\Migration;

/**
 * Class m180719_193417_add_blog_posts_count_comments_field
 */
class m180719_193417_add_blog_posts_count_comments_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%blog_posts}}', 'comments_count', $this->integer()->notNull());
        $this->update('{{%blog_posts}}', ['comments_count' => 0]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%blog_posts}}', 'comments_count');
    }

}
