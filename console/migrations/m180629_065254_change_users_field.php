<?php

use yii\db\Migration;

/**
 * Class m180629_065254_change_users_field
 */
class m180629_065254_change_users_field extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}', 'username', $this->string());
        $this->alterColumn('{{%user}}', 'password_hash', $this->string());
        $this->alterColumn('{{%user}}', 'email', $this->string());

    }

    public function down()
    {
        $this->alterColumn('{{%user}}', 'username', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'password_hash', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'email', $this->string()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180629_065254_change_users_field cannot be reverted.\n";

        return false;
    }
    */
}
