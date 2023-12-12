<?php

namespace app\controllers;

use app\models\Replies;
use app\models\UserSettings;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Tasks;
use app\models\Status;
use yii\db\Query;
use borpheus\utils\DateBack;
use yii\i18n\Formatter;
use app\models\TasksFilter;
use app\models\Categories;
use Faker\Factory;
use yii\web\NotFoundHttpException;
use yii\db\Migration;
use borpheus\utils\MyHelper;
use app\models\Users;
use app\models\Cities;

class SignupController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    if ($action->id == 'index') {
                        Yii::$app->session->setFlash('info', 'You are already logged in, no need to reset password');
                        return $action->controller->redirect('/tasks/');
                    }
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                },
            ]
        ];
    }


    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionIndex()
    {

        $citiesElements = Cities::find()->select('id, name')->orderBy(['id' => SORT_ASC])->all();
        $cities = array();
        foreach ($citiesElements as $city) {
            $cities[$city->id] = $city->name;
        }
        $this->layout = 'anon';
        $user = new Users();
        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());

            if (intval(Yii::$app->request->post('Users')['is_doer']) === 1) {
                $user->role_id = 2;
            } else {
                $user->role_id = 1;
            }

            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                $user->save(false);
                $us = new UserSettings();
                $us->attributes = [
                    'user_id' => $user->id
                ];
                $us->save(false);
                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }

        return $this->render('index', ['model' => $user, 'cities' => $cities]);
    }
}
