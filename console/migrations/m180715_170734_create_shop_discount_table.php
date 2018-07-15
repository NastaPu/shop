<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_discount`.
 */
class m180715_170734_create_shop_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('shop_discount', [
            'id' => $this->primaryKey(),
            'percent' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
            'active' => $this->boolean()->notNull(),
            'sort' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_discount');
    }
}
