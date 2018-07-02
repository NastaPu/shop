<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_brand`.
 */
class m180702_123011_create_shop_brand_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOption = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('shop_brand', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'meta_json' => 'JSON NOT NULL',
        ], $tableOption);
        $this->createIndex('{{%idx-shop_brand-slug}}', '{{%shop_brand}}', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_brand');
    }
}
