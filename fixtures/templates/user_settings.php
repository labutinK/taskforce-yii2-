<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\User;
use yii\db\Expression;
use app\models\Categories;

return [
    'name' => $faker->sentence,
    'category_id' => Categories::find()->select('id')->orderBy('RAND()')->one()->id,
    'description' => $faker->realTextBetween(),
    'budget' => rand(1000, 10000),
    'dt_add' => $faker->dateTimeBetween('-1 month')->format('Y-m-d'),
    'client_id' => 1,
    'expire_dt' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
    'status_id' => 1,

];
