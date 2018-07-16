<?php

use yii\db\Migration;

/**
 * Class m180716_151933_add_shop_product_fields
 */
class m180716_151933_add_shop_product_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%shop_product}}', 'weight', $this->integer()->notNull());
        $this->addColumn('{{%shop_product}}', 'quantity', $this->integer()->notNull());
        $this->addColumn('{{%shop_modification}}', 'quantity', $this->integer()->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%shop_product}}', 'weight');
        $this->dropColumn('{{%shop_product}}', 'quantity');
        $this->dropColumn('{{%shop_modification}}', 'quantity');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180716_151933_add_shop_product_fields cannot be reverted.\n";

        return false;
    }
    */
}
