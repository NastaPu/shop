<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_product`.
 */
class m180703_124700_create_shop_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('shop_product', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'price_old' => $this->integer(),
            'price_new' => $this->integer(),
            'rating' => $this->decimal(3, 2),
            'meta_json' => $this->text(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_product-code}}', '{{%shop_product}}', 'code', true);
        $this->createIndex('{{%idx-shop_product-category_id}}', '{{%shop_product}}', 'category_id');
        $this->createIndex('{{%idx-shop_product-brand_id}}', '{{%shop_product}}', 'brand_id');

        $this->addForeignKey('{{%fk-shop_product-category_id}}', '{{%shop_product}}', 'category_id', '{{%shop_category}}', 'id');
        $this->addForeignKey('{{%fk-shop_product-brand_id}}', '{{%shop_product}}', 'brand_id', '{{%shop_brand}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_product');
    }
}
