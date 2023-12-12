<?php
/**
 * @var $faker \Faker\Generator
 */
use yii\db\Migration;
use Faker\Factory;

/**
 * Class m231104_124302_tasksUpdated
 */
class m231104_124302_tasksUpdated extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Запрос к базе данных для обновления полей dt_add
        $faker = Factory::create();
        $this->update('Tasks', [
            'dt_add' => $faker->dateTimeBetween('-1 day')->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231104_124302_tasksUpdated cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231104_124302_tasksUpdated cannot be reverted.\n";

        return false;
    }
    */
}
