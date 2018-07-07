<?php

use yii\db\Migration;

/**
 * Class m180707_152126_add_shop_product_description_field
 */
class m180707_152126_add_shop_product_description_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%shop_product}}', 'description', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%shop_products}}', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180707_152126_add_shop_product_description_field cannot be reverted.\n";

        return false;
    }
    */
}
