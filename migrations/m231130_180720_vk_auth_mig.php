<?php

use yii\db\Migration;

/**
 * Class m231130_180720_vk_auth_mig
 */
class m231130_180720_vk_auth_mig extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'vk_id' => $this->integer()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-auth-user_id',  // Название ключа
            'auth',             // Таблица, к которой добавляется ключ
            'user_id',          // Поле, которое является внешним ключом
            'users',            // Таблица, на которую ссылается внешний ключ
            'id',               // Поле внешней таблицы, на которое ссылается внешний ключ
            'CASCADE'           // Опция удаления записи в родительской таблице (например, CASCADE, SET NULL, SET DEFAULT)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231130_180720_vk_auth_mig cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231130_180720_vk_auth_mig cannot be reverted.\n";

        return false;
    }
    */
}
