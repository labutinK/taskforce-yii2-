<?php

namespace app\models;

use borpheus\utils\MyHelper;
use yii\db\ActiveRecord;
use app\models\Tasks;
use app\models\Categories;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;
use DateTime;

class Profile extends Model
{
    public $name;
    public $email;
    public $birthday;
    public $phone;
    public $messenger;
    public $about;
    public $avatar;
    public $avatar_path;
    public $categories;
    public $old_password;
    public $password;
    public $password_retype;
    public $hide_contacts;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            [
                ['birthday'],
                'date',
                'format' => 'php:Y.m.d',
                'min' => date('Y.m.d', strtotime('-120 years')),
                'max' => date('Y.m.d', strtotime('-14 years')), // Максимальная дата (текущая дата)
                'message' => 'Некорректный формат даты. Используйте гггг.мм.дд.',
                'tooBig' => 'Вы слишком молоды, чтобы использовать этот сайт',
                'tooSmall' => 'Сомневаюсь, что вам больше 120 лет',
            ],
            ['phone', 'match', 'pattern' => '/^[0-9]{11}$/i', 'message' => 'Неверный формат телефонного номера.'],
            ['messenger', 'string', 'max' => 64],
            ['about', 'string', 'max' => 440],
            [['avatar'], 'file', 'extensions' => 'jpg, png, jpeg'],
            [['categories'], 'each', 'rule' => ['exist', 'targetClass' => Categories::class, 'targetAttribute' => 'id']],
            ['password', 'string', 'min' => 8, 'max' => 32, 'skipOnEmpty' => true],
            [['password', 'password_retype'], 'validateOldPasswordExist', 'skipOnEmpty' => false],
            ['password_retype', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => true],
            ['old_password', 'validateOldPassword'],
            ['hide_contacts', 'filter', 'filter' => 'intval'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'avatar' => 'Аватар',
            'email' => 'Email',
            'birthday' => 'День рождения',
            'phone' => 'Номер телефона',
            'messenger' => 'Telegram',
            'about' => 'Информация о себе',
            'categories' => 'Выбор специализаций',
            'password' => 'Новый пароль',
            'password_retype' => 'Повтор нового пароля',
            'old_password' => 'Старый пароль',
            'hide_contacts' => 'Скрыть мои контакты ото всех кроме участников сделок с моим участием'
        ];
    }

    public function validateOldPasswordExist($attribute, $params)
    {
        if (empty($this->old_password) && empty($this->password) && empty($this->password_retype)) {
            $this->clearErrors('old_password');
            $this->clearErrors('password');
            $this->clearErrors('password_retype');
        } else {
            if (empty($this->old_password)) {
                $this->addError('old_password', 'Введите старый пароль');
            }
            if (empty($this->password)) {
                $this->addError('password', 'Введите новый пароль');
            }
            if (empty($this->password_retype)) {
                $this->addError('password_retype', 'Введите новый пароль');
            }
        }

    }

    public function validateOldPassword($attribute, $params)
    {
        if (empty($this->old_password) && empty($this->password) && empty($this->password_retype)) {
            $this->clearErrors('old_password');
            $this->clearErrors('password');
            $this->clearErrors('password_retype');
        }
        // Проверка старого пароля
        $user = Users::findOne(Yii::$app->user->id);
        if (!$user || !$user->validatePassword($this->old_password)) {
            $this->addError($attribute, 'Неправильный пароль');
        } else {
            $this->clearErrors($attribute);
        }
    }


    /**
     * @return void
     */
    public function uploadPhoto()
    {
        if (!empty($this->avatar)) {
            $src = '@webroot/uploads/' . $this->avatar->baseName . '_' . uniqid('upload') . '.' . $this->avatar->extension;
            $this->avatar->saveAs($src);
            $this->avatar_path = str_replace('@webroot', '', $src);
        }
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return Categories::find()->select(['name', 'id'])->indexBy('id')->column();
    }

    /**
     * @return void
     */
    public function loadData()
    {
        $user = Users::findOne(Yii::$app->user->id);
        $userSettings = UserSettings::findOne(['user_id' => $user->id]);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->old_password = '';
        if ($userSettings->bd) {
            $this->birthday = date('Y.m.d', strtotime($userSettings->bd));
        }
        $this->phone = $userSettings->phone;
        $this->hide_contacts = $userSettings->opt_hide_contacts;
        $this->messenger = $userSettings->messenger;
        $this->about = $userSettings->about;
        $this->avatar_path = $userSettings->avatar_path;
        $this->categories = $user->userCategoriesRefactor;
    }

    public function updateSafeData()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        var_dump($this->hide_contacts);
        try {
            $user = Users::findOne(Yii::$app->user->id);
            $userSettings = UserSettings::findOne(['user_id' => $user->id]);

            if (!empty($this->password)) {
                $user->password = Yii::$app->security->generatePasswordHash($this->password);

                if (!$user->save(false)) {
                    $transaction->rollBack();
                    return false;
                }
            }
            $userSettings->opt_hide_contacts = $this->hide_contacts ? 1 : 0;
            if (!$userSettings->save(false)) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function updateUserData()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $user = Users::findOne(Yii::$app->user->id);
            $userSettings = UserSettings::findOne(['user_id' => $user->id]);
            $this->uploadPhoto();

            $user->email = $this->email;
            $user->name = $this->name;

            if (!$user->save(false)) {
                $transaction->rollBack();
                return false;
            }

            $userSettings->phone = $this->phone;
            $userSettings->messenger = $this->messenger;
            $userSettings->avatar_path = $this->avatar_path;
            $userSettings->about = $this->about;
            $userSettings->bd = $this->birthday;
            if (!$userSettings->save(false)) {
                $transaction->rollBack();
                return false;
            }


            $chosenCategories = $this->categories;
            $userCategories = UserCategories::find()->select(['id', 'category_id'])->where(['user_id' => Yii::$app->user->id])->asArray()->all();
            if (!empty($userCategories)) {
                foreach ($userCategories as $userCategory) {
                    if (!in_array($userCategory['category_id'], $chosenCategories)) {
                        $nonExistCategory = UserCategories::findOne($userCategory['id']);
                        if (!$nonExistCategory->delete()) {
                            $transaction->rollBack();
                            return false;
                        }
                    } else {
                        $chosenCategories = array_diff($chosenCategories, [$userCategory['category_id']]);
                    }
                }
            }

            if (!empty($chosenCategories)) {
                foreach ($chosenCategories as $newCategory) {
                    $newUserCategory = new UserCategories();
                    $newUserCategory->user_id = Yii::$app->user->id;
                    $newUserCategory->category_id = $newCategory;
                    if (!$newUserCategory->save(false)) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            // Commit the transaction if everything is successful
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            // If an exception occurs, roll back the transaction
            $transaction->rollBack();
            return false;
        }
    }
}