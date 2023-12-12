<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Cities;
use yii\db\Expression;

return [
    'name' => $faker->unique()->city,
];