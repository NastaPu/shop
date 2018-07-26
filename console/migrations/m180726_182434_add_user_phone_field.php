<?php

use yii\db\Migration;

/**
 * Class m180726_182434_add_user_phone_field
 */
class m180726_182434_add_user_phone_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%user}}', 'phone', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%user}}', 'phone');
    }
}
