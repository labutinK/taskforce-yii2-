<?php

use yii\db\Migration;
use app\models\Status;

/**
 * Class m231101_183756_test_migration
 */
class m231101_183756_test_migration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Status', 'name_display', $this->string(256));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('Status', 'name_display');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231101_183756_test_migration cannot be reverted.\n";

        return false;
    }
    */
}
