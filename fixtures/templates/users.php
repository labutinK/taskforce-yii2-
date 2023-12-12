<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Users;
use yii\db\Expression;

return [
    'email' => $faker->unique()->safeEmail,
    'name' => $faker->name,
    'city_id' => $faker->numberBetween(1, 10), // Замените диапазоном на реальные значения
    'password' => $faker->regexify('[A-Za-z0-9]{64}'), // Генерируем 64 символьный пароль
    'blocked' => false, // Генерируем случайное значение true или false
    'dt_add' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'), // Генерируем случайную дату в текущем году
    'last_activity' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'), // Генерируем случайную дату в текущем году
    'role_id' => $faker->numberBetween(1, 2),
];