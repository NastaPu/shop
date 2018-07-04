<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_modification`.
 */
class m180704_114732_create_shop_modification_table extends Migration
{

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('shop_modification', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'price' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_modification-code}}', '{{%shop_modification}}', 'code');
        $this->createIndex('{{%idx-shop_modification-product_id-code}}', '{{%shop_modification}}', ['product_id', 'code'], true);
        $this->createIndex('{{%idx-shop_modification-product_id}}', '{{%shop_modification}}', 'product_id');

        $this->addForeignKey('{{%fk-shop_modification-product_id}}', '{{%shop_modification}}', 'product_id', '{{%shop_product}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_modification');
    }
}
