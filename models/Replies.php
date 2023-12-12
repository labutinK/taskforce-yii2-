<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "replies".
 *
 * @property int $id
 * @property int $user_id
 * @property string $dt_add
 * @property string $description
 * @property int $task_id
 * @property int|null $is_approved
 *
 * @property Tasks $task
 * @property Users $user
 */
class Replies extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'replies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'description', 'task_id', 'sum'], 'required'],
            [['user_id', 'task_id', 'is_approved', 'is_denied'], 'integer'],
            [['dt_add'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Tasks::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
            [
                'sum',
                'integer',
                'min' => 1,
                'when' => function ($model) {
                    return !empty($model->sum);
                },
                'message' => 'Стоимость должна быть целым положительным числом'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'dt_add' => 'Dt Add',
            'description' => 'Ваш комментарий',
            'task_id' => 'Task ID',
            'is_approved' => 'Is Approved',
            'is_denied' => 'Is Denied',
            'sum' => 'Стоимость',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
}
