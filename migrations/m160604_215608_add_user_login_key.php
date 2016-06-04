<?php

use yii\db\Migration;

class m160604_215608_add_user_login_key extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'login_key', 'string(32) NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'login_key');
    }
}
