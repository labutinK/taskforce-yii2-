<?php

namespace app\models;

use Yii;
use app\models\Categories;
use app\models\Files;
use DateTime;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string $description
 * @property string|null $location
 * @property int|null $budget
 * @property string|null $expire_dt
 * @property string|null $dt_add
 * @property int $client_id
 * @property int|null $performer_id
 * @property int $status_id
 *
 * @property Categories $category
 * @property Events[] $events
 * @property Files[] $files
 * @property Messages[] $messages
 * @property Replies[] $replies
 * @property Statuses $status
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'description', 'client_id', 'status_id', 'budget', 'expire_dt'], 'required'],
            [['category_id', 'budget', 'client_id', 'performer_id', 'status_id'], 'integer'],
            [['description'], 'string'],
            [['expire_dt', 'dt_add'], 'safe'],
            [
                'budget',
                'integer',
                'min' => 1,
                'when' => function ($model) {
                    return !empty($model->budget);
                },
                'message' => 'Бюджет должен быть целым числом больше нуля'
            ],
            [
                ['expire_dt'],
                'date',
                'format' => 'php:Y.m.d',
                'min' => date('Y.m.d'),
                'message' => 'Некорректный формат даты. Используйте гггг.мм.дд.'
            ],
            [['name', 'location'], 'string', 'max' => 255],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Categories::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['status_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Status::class,
                'targetAttribute' => ['status_id' => 'id']
            ],
            [['files'], 'file', 'extensions' => 'pdf, docx', 'maxFiles' => 4]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Cуть работы',
            'category_id' => 'Категория',
            'description' => 'Подробности задания',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'expire_dt' => 'Срок исполнения',
            'dt_add' => 'Dt Add',
            'client_id' => 'Client ID',
            'performer_id' => 'Performer ID',
            'status_id' => 'Status ID',
            'files' => 'Файлы',
        ];
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $dateString = $this->expire_dt;
        $format = 'Y-m-d H:i:s';

        $dateTime = DateTime::createFromFormat($format, $dateString);

        if ($dateTime === false) {
            // Если не удалось создать объект DateTime, предполагаем формат 'Y-m-d' и добавляем время.
            $dateTime = DateTime::createFromFormat('Y.m.d H:i', $this->expire_dt . ' 23:00');
            $this->expire_dt = $dateTime->format('Y-m-d H:i:s');
        }

        return true;
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoriesQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery|EventsQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Events::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|FilesQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery|MessagesQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['task_id' => 'id']);
    }


    public function upload($taskId, $currentUser)
    {
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $src = '@webroot/uploads/' . $file->baseName . '_' . uniqid('upload') . '.' . $file->extension;
                $file->saveAs($src);
                $props = [
                    'name' => $file->baseName . '.' . $file->extension,
                    'path' => str_replace('@webroot', '', $src),
                    'task_id' => $taskId,
                    'user_id' => $currentUser,
                ];
                $file = new Files();
                $file->attributes = $props;
                $file->save();
            }
        }
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery|StatusesQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Cityies]].
     *
     * @return \yii\db\ActiveQuery|CitiesQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * {@inheritdoc}
     * @return TasksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TasksQuery(get_called_class());
    }


    public static function getCategories()
    {
        $cats = Categories::find()->all();
        return \yii\helpers\ArrayHelper::map($cats, 'id', 'name');
    }
}
