<?php

namespace app\models;

use yii\base\Model;
use app\models\Users;

class RoleForm extends Model
{

    public $role_id;

    public function rules()
    {
        return [
            [['role_id'], 'required', 'message' => 'Необходимо выбрать роль'],
            [['role_id'], 'integer'],
            [
                'role_id',
                'exist',
                'skipOnError' => true,
                'targetClass' => Role::class,
                'targetAttribute' => ['role_id' => 'id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'role_id' => 'Выберите роль',
        ];
    }
}