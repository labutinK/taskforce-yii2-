<?php

use yii\db\Migration;

/**
 * Class m231103_184652_remove_id_column_from_cities_table
 */
class m231103_184652_remove_id_column_from_cities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('cities', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231103_184652_remove_id_column_from_cities_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231103_184652_remove_id_column_from_cities_table cannot be reverted.\n";

        return false;
    }
    */
}
