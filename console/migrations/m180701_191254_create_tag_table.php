<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag`.
 */
class m180701_191254_create_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%shop_tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ], $tableOptions);
        $this->createIndex('{{%idx-shop_tag-slug}}', '{{%shop_tag}}', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_tag');
    }
}
