<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_characteristic`.
 */
class m180703_091410_create_shop_characteristic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOption = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('shop_characteristic', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'required' => $this->boolean()->notNull(),
            'default' => $this->string(),
            'variants_json' => 'JSON NOT NULL',
            'sort' => $this->integer()->notNull(),
        ], $tableOption);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('shop_characteristic');
    }
}
