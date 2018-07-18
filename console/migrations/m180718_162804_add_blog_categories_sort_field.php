<?php

use yii\db\Migration;

/**
 * Class m180718_162804_add_blog_categories_sort_field
 */
class m180718_162804_add_blog_categories_sort_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%blog_categories}}', 'sort', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%blog_categories}}', 'sort');
    }
}
