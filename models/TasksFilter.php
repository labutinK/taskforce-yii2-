<?php

namespace app\models;

use borpheus\utils\MyHelper;
use yii\db\ActiveRecord;
use app\models\Tasks;
use app\models\Categories;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

class TasksFilter extends Model
{
    public $category_name;
    public $without_customer;
    public $period;
    public $status;

    public function attributeLabels()
    {
        return [
            'category_name' => 'Категории',
            'without_customer' => 'Дополнительно',
            'period' => 'Период',
        ];
    }

    /**
     * @return array
     */
    static function getTasksStatuses()
    {
        return Status::find()
            ->select('status.name')
            ->leftJoin('tasks', 'tasks.status_id = status.id')
            ->andWhere(['or',
                ['tasks.client_id' => Yii::$app->user->id],
                ['tasks.performer_id' => Yii::$app->user->id]
            ])
            ->distinct()
            ->column();
    }


    public function getCategoryNames()
    {
        $cats = Categories::find()
            ->joinWith('tasks') // Присоединяем связь с задачами
            ->where(['tasks.status_id' => 1]) // Фильтр по статусу задачи
            ->all();
        return \yii\helpers\ArrayHelper::map($cats, 'id', 'name');
    }
}