<?php

use yii\db\Migration;

/**
 * Class m231103_180850_add_role_id_to_users
 */
class m231103_180850_add_role_id_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'role_id', $this->integer());

        // Создайте индекс для столбца role_id
        $this->createIndex('idx-users-role_id', 'users', 'role_id');

        // Создайте внешний ключ, связывающий role_id с таблицей role
        $this->addForeignKey('fk-users-role_id', 'users', 'role_id', 'role', 'id', 'CASCADE', 'CASCADE');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешний ключ
        $this->dropForeignKey('fk-users-role_id', 'users');

        // Удаляем индекс
        $this->dropIndex('idx-users-role_id', 'users');

        // Удаляем столбец role_id
        $this->dropColumn('users', 'role_id');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231103_180850_add_role_id_to_users cannot be reverted.\n";

        return false;
    }
    */
}
