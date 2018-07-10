<?php

use yii\db\Migration;

/**
 * Class m180710_153540_add_shop_product_status
 */
class m180710_153540_add_shop_product_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%shop_product}}', 'status', $this->smallInteger()->notNull());
        $this->update('{{%shop_product}}', ['status' => 1]);

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%shop_product}}', 'status');
    }
}
