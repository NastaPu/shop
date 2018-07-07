<?php

use yii\db\Migration;

/**
 * Class m180707_145053_add_shop_product_main_photo_field
 */
class m180707_145053_add_shop_product_main_photo_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%shop_product}}', 'main_photo_id', $this->integer());
        $this->createIndex('{{%idx-shop_product-main_photo_id}}', '{{%shop_product}}', 'main_photo_id');
        $this->addForeignKey('{{%fk-shop_product-main_photo_id}}', '{{%shop_product}}', 'main_photo_id', '{{%shop_photo}}', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('{{%fk-shop_product-main_photo_id}}', '{{%shop_product}}');
        $this->dropColumn('{{%shop_product}}', 'main_photo_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180707_145053_add_shop_product_main_photo_field cannot be reverted.\n";

        return false;
    }
    */
}
