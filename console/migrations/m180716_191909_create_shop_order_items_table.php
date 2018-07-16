<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_order_items`.
 */
class m180716_191909_create_shop_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_order_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer(),
            'modification_id' => $this->integer(),
            'product_name' => $this->string()->notNull(),
            'product_code' => $this->string()->notNull(),
            'modification_name' => $this->string(),
            'modification_code' => $this->string(),
            'price' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_order_items-order_id}}', '{{%shop_order_items}}', 'order_id');
        $this->createIndex('{{%idx-shop_order_items-product_id}}', '{{%shop_order_items}}', 'product_id');
        $this->createIndex('{{%idx-shop_order_items-modification_id}}', '{{%shop_order_items}}', 'modification_id');

        $this->addForeignKey('{{%fk-shop_order_items-order_id}}', '{{%shop_order_items}}', 'order_id', '{{%shop_orders}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-shop_order_items-product_id}}', '{{%shop_order_items}}', 'product_id', '{{%shop_product}}', 'id', 'SET NULL');
        $this->addForeignKey('{{%fk-shop_order_items-modification_id}}', '{{%shop_order_items}}', 'modification_id', '{{%shop_modification}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_order_items}}');
    }
}
