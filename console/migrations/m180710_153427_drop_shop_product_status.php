<?php

use yii\db\Migration;

/**
 * Class m180710_153427_drop_shop_product_status
 */
class m180710_153427_drop_shop_product_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropColumn('{{%shop_product}}','status');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m180710_153427_drop_shop_product_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180710_153427_drop_shop_product_status cannot be reverted.\n";

        return false;
    }
    */
}
