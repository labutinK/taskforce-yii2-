<?php

namespace app\models;

use app\models\Auth;
use borpheus\utils\MyHelper;
use Yii;
use yii\web\IdentityInterface;
use app\models\Auth as Auuth;
use function Symfony\Component\String\u;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property int $city_id
 * @property string $password
 * @property string $dt_add
 * @property int $blocked
 * @property string|null $last_activity
 *
 * @property Bookmarks[] $bookmarks
 * @property Bookmarks[] $bookmarks0
 * @property Cities $city
 * @property Events[] $events
 * @property Files[] $files
 * @property Messages[] $messages
 * @property Messages[] $messages0
 * @property Opinions[] $opinions
 * @property Opinions[] $opinions0
 * @property Replies[] $replies
 * @property int $role_id
 * @property int $is_doer
 * @property UserCategories[] $userCategories
 * @property UserSettings $userSettings
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $is_doer;

    public $password_retype;

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'city_id', 'password', 'password_retype'], 'required'],
            [['city_id', 'blocked'], 'integer'],
            [['dt_add', 'last_activity'], 'safe'],
            ['email', 'email'],
            ['email', 'unique'],
            ['password', 'string', 'min' => 8, 'max' => 32],
            ['password_retype', 'compare', 'compareAttribute' => 'password'],
            [
                'city_id',
                'exist',
                'skipOnError' => true,
                'targetClass' => Cities::class,
                'targetAttribute' => ['city_id' => 'id']
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
            'email' => 'Email',
            'name' => 'Имя',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_retype' => 'Повтор пароля',
            'dt_add' => 'Зарегистрирован',
            'blocked' => 'Заблокирован',
            'last_activity' => 'Последняя активность',
            'role_id' => 'Роль',
            'is_doer' => 'я собираюсь откликаться на заказы'
        ];
    }


    /**
     * Gets query for [[Bookmarks]].
     *
     * @return \yii\db\ActiveQuery|BookmarksQuery
     */
    public function getBookmarks()
    {
        return $this->hasMany(Bookmarks::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Bookmarks0]].
     *
     * @return \yii\db\ActiveQuery|BookmarksQuery
     */
    public function getBookmarks0()
    {
        return $this->hasMany(Bookmarks::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CitiesQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }


    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery|EventsQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Events::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|FilesQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery|MessagesQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery|MessagesQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Messages::class, ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions]].
     *
     * @return \yii\db\ActiveQuery|OpinionsQuery
     */
    public function getMyOpinions()
    {
        return $this->hasMany(Opinions::class, ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions0]].
     *
     * @return \yii\db\ActiveQuery|OpinionsQuery
     */
    public function getOpinionsAboutMe()
    {
        return $this->hasMany(Opinions::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery|RepliesQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery|UserCategoriesQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategories::class, ['user_id' => 'id']);
    }

    /**
     * @return string|null
     */
    public function getAvatarPath()
    {
        return UserSettings::findOne(['user_id' => $this->id])->avatar_path;
    }


    /**
     * @return array
     */
    public function getUserCategoriesRefactor()
    {
        return $this->getUserCategories()->select(['category_id'])->column();
    }

    /**
     * @return array
     */
    public function getUserCategoriesNames()
    {
        $catsIds = $this->getUserCategories()->select(['category_id'])->column();

        return Categories::find()->where(['id' => $catsIds])->select('name')->column();
    }

    /**
     * Gets query for [[UserSettings]].
     *
     * @return \yii\db\ActiveQuery|UserSettingsQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::class, ['user_id' => 'id']);
    }


    public static function canViewContacts($user)
    {
        $hideContacts = boolval($user->userSettings->opt_hide_contacts);
        if (!$hideContacts || Yii::$app->user->id === $user->id) {
            return true;
        }
        return Tasks::find()
            ->where(['performer_id' => $user->id, 'client_id' => Yii::$app->user->id])
            ->orWhere(['performer_id' => Yii::$app->user->id, 'client_id' => $user->id])->exists();
    }

    /**
     * @return bool
     */
    public static function userFromSocialNetworks()
    {
        return boolval(Auuth::find()->where(['user_id' => Yii::$app->user->id])->exists());
    }

    /**
     * Gets roleId
     *
     * @return
     */
    public static function isCustomer()
    {
        return Yii::$app->user->identity->role_id === 1;
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
