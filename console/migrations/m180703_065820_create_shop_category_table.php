<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_category`.
 */
class m180703_065820_create_shop_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOption = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('shop_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'meta_json' => 'JSON NOT NULL',
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOption);

        $this->createIndex('{{%idx-shop_category-slug}}', '{{%shop_category}}', 'slug', true);

        $this->insert('{{%shop_category}}', [
            'id' => 1,
            'name' => '',
            'slug' => 'root',
            'title' => null,
            'description' => null,
            'meta_json' => '{}',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_category');
    }
}
