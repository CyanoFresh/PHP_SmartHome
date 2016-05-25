<?php

use app\models\User;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        // Create table
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Register user
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@domain.com';
        $user->setPassword('admin');
        $user->generateAuthKey();

        return $user->save();
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
