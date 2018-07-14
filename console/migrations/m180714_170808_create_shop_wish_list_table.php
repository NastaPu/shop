<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_wish_list`.
 */
class m180714_170808_create_shop_wish_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%shop_wish_list}}', [
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-shop_wish_list}}', '{{%shop_wish_list}}', ['user_id', 'product_id']);
        $this->createIndex('{{%idx-shop_wish_list-user_id}}', '{{%shop_wish_list}}', 'user_id');
        $this->createIndex('{{%idx-shop_wish_list-product_id}}', '{{%shop_wish_list}}', 'product_id');
        $this->addForeignKey('{{%fk-shop_wish_list-user_id}}', '{{%shop_wish_list}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_wish_list-product_id}}', '{{%shop_wish_list}}', 'product_id', '{{%shop_product}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_wish_list');
    }
}
