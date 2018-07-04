<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_photo`.
 */
class m180703_160131_create_shop_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%shop_photo}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('{{%idx-shop_photo-product_id}}', '{{%shop_photo}}', 'product_id');
        $this->addForeignKey('{{%fk-shop_photo-product_id}}', '{{%shop_photo}}', 'product_id', '{{%shop_product}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_photo');
    }
}
