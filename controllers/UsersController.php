<?php

namespace app\controllers;

use app\models\Auth;
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
use yii\widgets\ActiveForm;
use app\models\Profile;
use yii\web\UploadedFile;

class UsersController extends SecuredController
{

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $user = Users::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Пользователь с ID $id не найден");
        }

        $tasksDone = Tasks::find()->where(['status_id' => 3, 'performer_id' => $id])->count();
        $tasksFailed = Tasks::find()->where(['status_id' => 5, 'performer_id' => $id])->count();

        return $this->render('view', ['user' => $user, 'taskDone' => $tasksDone, 'tasksFailed' => $tasksFailed]);
    }


    /**
     * @return array|string
     */
    public function actionEdit()
    {

        $form_model = new Profile();
        $form_model->loadData();

        if (Yii::$app->request->getIsPost()) {
            $form_model->load(\Yii::$app->request->post());
            if (\Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($form_model);
            }
            if ($form_model->validate()) {
                $form_model->avatar = UploadedFile::getInstance($form_model, 'avatar');
                if ($form_model->updateUserData()) {
                    $form_model->loadData();
                }
            }
        }
        return $this->render('edit', ['model' => $form_model]);

    }

    /**
     * @return array|string
     */
    public function actionSafe()
    {
        $form_model = new Profile();
        $form_model->loadData();
        if (Yii::$app->request->getIsPost()) {
            $form_model->load(\Yii::$app->request->post());

            if (\Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($form_model);
            }
            if ($form_model->validate()) {
                try {
                    if ($form_model->updateSafeData()) {
                        $form_model->loadData();
                        return $this->redirect(['users/view', 'id' => Yii::$app->user->id]);
                    } else {
                        throw new \Exception("Ошибка сохранения данных");
                    }
                } catch (\Exception $e) {
                    // Handle other exceptions
                    $errorMessage = $e->getMessage();

                    // Сохраняем сообщение об ошибке в сессии
                    Yii::$app->session->setFlash('error', $errorMessage);

                    $this->layout = 'anon';
                    // Рендерим страницу с выводом ошибки
                    return $this->render('error', [
                        'name' => 'Error',
                        'message' => $errorMessage,
                    ]);
                }
            }
        }

        return $this->render('safe', ['model' => $form_model]);
    }


}
