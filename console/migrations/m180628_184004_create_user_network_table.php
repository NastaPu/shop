<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_network`.
 */
class m180628_184004_create_user_network_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%user_network}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'identity' => $this->string()->notNull(),
            'network' => $this->string(16)->notNull(),
        ], $tableOptions);
        $this->createIndex('{{%idx-user_network-identity-name}}', '{{%user_network}}', ['identity', 'network'], true);
        $this->createIndex('{{%idx-user_network-user_id}}', '{{%user_network}}', 'user_id');
        $this->addForeignKey('{{%fk-user_network-user_id}}', '{{%user_network}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('user_network');
    }
}
