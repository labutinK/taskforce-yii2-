<?php

use yii\db\Migration;

/**
 * Class m231103_184846_remove_foreign_key_from_users
 */
class m231103_184846_remove_foreign_key_from_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_users_cities_1', 'users'); //
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231103_184846_remove_foreign_key_from_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231103_184846_remove_foreign_key_from_users cannot be reverted.\n";

        return false;
    }
    */
}
