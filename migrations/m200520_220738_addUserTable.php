<?php

use yii\db\Migration;

/**
 * Class m200520_220738_addUserTable
 */
class m200520_220738_addUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'login' => $this->string(),
            'password' => $this->string(),
            'auth_key' => $this->string(),
            'date_register' => $this->string(),
            'date_last_login' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        echo "m200520_220738_addUserTable cannot be reverted.\n";
        $this->dropTable('users');
  //      return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200520_220738_addUserTable cannot be reverted.\n";

        return false;
    }
    */
}
