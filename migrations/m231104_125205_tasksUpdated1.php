<?php

use yii\db\Migration;
use Faker\Factory;

/**
 * Class m231104_125205_tasksUpdated1
 */
class m231104_125205_tasksUpdated1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $faker = Factory::create();

        // Fetch all records from the 'Tasks' table
        $tasks = (new \yii\db\Query())
            ->select(['id'])
            ->from('Tasks')
            ->all();

        foreach ($tasks as $task) {
            $randomTime = $faker->dateTimeBetween('-1 day')->format('Y-m-d H:i:s');
            $this->update('Tasks', ['dt_add' => $randomTime], ['id' => $task['id']]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231104_125205_tasksUpdated1 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231104_125205_tasksUpdated1 cannot be reverted.\n";

        return false;
    }
    */
}
