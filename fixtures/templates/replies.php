<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Users;
use yii\db\Expression;
use app\models\Tasks;


return [
    'user_id' => $randomUserId = Users::find()
        ->select(['id'])
        ->where(['role_id' => 2])
        ->orderBy('RAND()') // Случайное упорядочивание
        ->limit(1) // Ограничение выборки одним элементом
        ->scalar(),
    'dt_add' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
    'description' => $faker->realTextBetween(),
    'task_id' => Tasks::find()
        ->select(['id'])
        ->orderBy('RAND()') // Случайное упорядочивание
        ->limit(1) // Ограничение выборки одним элементом
        ->scalar(),
    'is_approved' => 0,
];