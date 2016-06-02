<?php

use app\models\User;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        // Create table
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),

            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_ACTIVE),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Register admin user
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@domain.com';
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->save();

        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'pin' => $this->smallInteger()->notNull(),
            'name' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'icon' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'value' => $this->smallInteger()->notNull(),
            'date' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%item}}');
        $this->dropTable('{{%log}}');
    }
}
