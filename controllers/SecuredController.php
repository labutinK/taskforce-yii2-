<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

abstract class SecuredController extends Controller
{

    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = Yii::$app->homeUrl;
    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->identity && is_null(Yii::$app->user->identity->role_id)) {
                        return $action->controller->redirect('/site/choose-role');
                    }
                    else{
                        return $action->controller->redirect('/');
                    }
                },
            ]
        ];
    }
}
