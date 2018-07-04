<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_review`.
 */
class m180704_120750_create_shop_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('shop_review', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'vote' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_review-product_id}}', '{{%shop_review}}', 'product_id');
        $this->createIndex('{{%idx-shop_review-user_id}}', '{{%shop_review}}', 'user_id');

        $this->addForeignKey('{{%fk-shop_review-product_id}}', '{{%shop_review}}', 'product_id', '{{%shop_product}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_review-user_id}}', '{{%shop_review}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_review');
    }
}
