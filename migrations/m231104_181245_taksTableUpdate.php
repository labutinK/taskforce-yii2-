<?php

use yii\db\Migration;

/**
 * Class m231104_181245_taksTableUpdate
 */
class m231104_181245_taksTableUpdate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем новую колонку 'city' в таблицу 'tasks'
        $this->addColumn('tasks', 'city', $this->string(256));

        // Заменяем значения в колонке 'location' на новые значения с использованием $faker
        $faker = \Faker\Factory::create();
        $tasks = \app\models\Tasks::find()->all();

        foreach ($tasks as $task) {
            $newLocation = $faker->address; // Пример, как можно сгенерировать новое значение с помощью $faker
            $task->city = $task->location;
            $task->location = $newLocation;
            $task->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231104_181245_taksTableUpdate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231104_181245_taksTableUpdate cannot be reverted.\n";

        return false;
    }
    */
}
