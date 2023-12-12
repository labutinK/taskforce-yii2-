<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "opinions".
 *
 * @property int $id
 * @property int $owner_id
 * @property int $performer_id
 * @property int $rate
 * @property string $description
 * @property string|null $dt_add
 * @property int|null $task_id
 *
 * @property Users $owner
 * @property Users $performer
 * @property Tasks $task
 */
class Opinions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opinions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_id', 'performer_id', 'rate', 'description'], 'required'],
            [['owner_id', 'performer_id', 'rate', 'task_id'], 'integer'],
            [['description'], 'string'],
            [['dt_add'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['owner_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['performer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'performer_id' => 'Performer ID',
            'rate' => 'Rate',
            'description' => 'Description',
            'dt_add' => 'Dt Add',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getOwner()
    {
        return $this->hasOne(Users::class, ['id' => 'owner_id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::class, ['id' => 'performer_id']);
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
     * {@inheritdoc}
     * @return OpinionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OpinionsQuery(get_called_class());
    }
}
