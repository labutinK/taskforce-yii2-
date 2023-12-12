<?php

namespace app\controllers;

use yii\base\Security;
use app\models\Opinions;
use app\models\Replies;
use app\models\Users;
use app\models\UserSettings;
use Yii;
use yii\db\StaleObjectException;
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
use function PHPUnit\Framework\fileExists;
use DateTime;
use yii\web\UploadedFile;
use borpheus\logic\TaskForce;
use borpheus\logic\action\CancelAction;
use borpheus\logic\action\CompleteAction;
use borpheus\logic\action\RefuseAction;
use borpheus\logic\action\RespondAction;
use GuzzleHttp\Client;
use yii\data\ActiveDataProvider;


const TASK_NEW_STATUS = 1;
const TASK_IN_PROCESS_STATUS = 4;

class TasksController extends SecuredController
{

    public function behaviors()
    {
        $rules = parent::behaviors();
        $rulesArray = [
            0 =>
                [
                    'allow' => false,
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity && is_null(Yii::$app->user->identity->role_id);
                    }
                ],
            1 =>
                [
                    'allow' => false,
                    'actions' => ['cancel'],
                    'matchCallback' => function ($rule, $action) {
                        $id = Yii::$app->request->get('id');
                        $task = Tasks::findOne($id);
                        $curUserId = Yii::$app->user->id;
                        return ($task->client_id !== $curUserId) || ($task->status->id !== TASK_NEW_STATUS);
                    }
                ],
            2 =>
                [
                    'allow' => false,
                    'actions' => ['refuse'],
                    'matchCallback' => function ($rule, $action) {
                        $id = Yii::$app->request->get('id');
                        $task = Tasks::findOne($id);
                        $curUserId = Yii::$app->user->id;
                        return ($task->performer_id !== $curUserId) || ($task->status->id !== TASK_IN_PROCESS_STATUS);
                    }
                ],
            3 =>
                [
                    'allow' => false,
                    'actions' => ['complete'],
                    'matchCallback' => function ($rule, $action) {
                        $taskId = Yii::$app->request->post('taskId');
                        $task = Tasks::findOne($taskId);
                        $curUserId = Yii::$app->user->id;
                        return (!Yii::$app->request->getIsPost() || $task->client_id !== $curUserId || $task->status->id !== TASK_IN_PROCESS_STATUS);
                    }
                ],
            4 =>
                [
                    'allow' => false,
                    'actions' => ['new-reply'],
                    'matchCallback' => function ($rule, $action) {
                        $taskId = Yii::$app->request->post('taskId');
                        $curUserId = Yii::$app->user->id;
                        $task = Tasks::findOne($taskId);
                        return Users::findOne($curUserId)->role_id !== 2 || $task->status->id !== TASK_NEW_STATUS;
                    }
                ],
        ];
        foreach ($rulesArray as $rule) {
            array_unshift($rules['access']['rules'], $rule);
        }

        return $rules;
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionAddTask()
    {
        $task = new Tasks();

        if (Yii::$app->request->getIsPost()) {
            $task->load(Yii::$app->request->post());
            $task->files = UploadedFile::getInstances($task, 'files');
            $current_user = Yii::$app->user->id;
            $task->client_id = $current_user;
            $task->status_id = TASK_NEW_STATUS;
            if ($task->validate()) {
                $task->save(false);
                $taskId = $task->id;
                $task->upload($taskId, $current_user);
                return $this->redirect(["tasks/view/$taskId"]);
            }
        }

        return $this->render('add-task', ['model' => $task]);
    }

    /**
     * @return void|Response
     */
    public function actionNewReply()
    {
        $myReply = new Replies();

        if (Yii::$app->request->getIsPost()) {
            $taskId = Yii::$app->request->post('taskId');
            $curUserId = Yii::$app->user->id;
            $task = Tasks::findOne($taskId);
            if ($taskId && $curUserId && $task) {
                $myReply->load(Yii::$app->request->post());
                $myReply->user_id = Yii::$app->user->id;
                $myReply->task_id = $taskId;
                if ($myReply->validate()) {
                    $myReply->save(false);
                }
            } else {
                throw new NotFoundHttpException("Ошибка, данные по задаче #$taskId не найдены, вероятно они устарели");
            }
            return $this->redirect(['tasks/view', 'id' => $taskId]);
        } else {
            throw new NotFoundHttpException("Техническая ошибка");
        }

    }

    protected function getStatusesProvider()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Status::find()
                ->leftJoin('tasks', 'tasks.status_id = status.id')
                ->andWhere(['or',
                    ['tasks.client_id' => Yii::$app->user->id],
                    ['tasks.performer_id' => Yii::$app->user->id]
                ]),
        ]);
        return $dataProvider;
    }

    public function actionMyTasks()
    {
        $status = Yii::$app->request->get('status');

        $query = Tasks::find()->where([
            'or',
            ['performer_id' => Yii::$app->user->id],
            ['client_id' => Yii::$app->user->id]
        ]);

        if ($status) {
            $query->andWhere(['status_id' => $status]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5],
            'sort' => ['defaultOrder' => ['expire_dt' => SORT_DESC]]
        ]);

        return $this->render('my-tasks', [
            'dataProvider' => $dataProvider,
            'dataStatusesProvider' => $this->getStatusesProvider()
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionIndex()
    {

        $query = new Query();
        $model = new TasksFilter();
        $query->select([
            't.id',
            't.dt_add',
            't.name',
            't.description',
            'performer_id',
            't.budget',
            't.location',
            't.category_id',
            'categories.name as category_name'
        ])->from('tasks as t')->join('INNER JOIN', 'categories', 't.category_id = categories.id')->where('status_id = 1');

        $query->limit(10);
        $query->orderBy([
            'dt_add' => SORT_DESC,
        ]);

        if ($model->load(Yii::$app->request->get())) {
            $selectedCategories = Yii::$app->request->get('TasksFilter')['category_name'];
            if (is_array($selectedCategories)) {
                $selectedCategories = array_filter($selectedCategories, function ($value) {
                    return $value != 0;
                });
            }
            if (!empty($selectedCategories)) {
                $query->andWhere(['category_id' => $selectedCategories]);
            }

            $without_customer_field = Yii::$app->request->get('TasksFilter')['without_customer'];
            if ($without_customer_field === 'Y') {
                $query->andWhere(['performer_id' => null]);
            }

            $period = Yii::$app->request->get('TasksFilter')['period'];
            if ($period) {
                $timestampXHoursAgo = time() - $period * 3600; // 3600 seconds in an hour
                $dateXHoursAgo = new \DateTime();
                $dateXHoursAgo->setTimestamp($timestampXHoursAgo);
                $query->andWhere([' >= ', 'dt_add', $dateXHoursAgo->format('Y-m-d H:i:s')]);
            }

            $model->category_name = $selectedCategories;
            $model->without_customer = $without_customer_field;
            $model->period = $period;
        } else {
            $model->category_name = '';
            $model->period = 0;
            $model->without_customer = '';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5],
        ]);


        $tasks = $query->all();
        if (!empty($tasks)) {
            $formatter = new Formatter();
            $formatter->locale = 'ru - RU'; // Установите локаль на русский
            foreach ($tasks as $key => $task) {
                $tasks[$key]['time_passed'] = DateBack::get_diff($task['dt_add']);
            }
        }

        return $this->render('index', ['tasks' => $tasks, 'model' => $model, 'dataProvider' => $dataProvider]);
    }

    /**
     * @param $task_id
     * @param $reply_id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDenyDoer($task_id, $reply_id)
    {
        $task = Tasks::findOne($task_id);
        $reply = Replies::findOne($reply_id);

        $curUserId = Yii::$app->user->id;

        if ($task && $reply && $task->client_id === $curUserId && $task->status_id = 1 && $reply->is_approved === 0 && $reply->is_denied === 0) {
            $reply->is_denied = 1;
            $reply->save(false);
            return $this->redirect(['tasks/view', 'id' => $task_id]);
        } else {
            throw new NotFoundHttpException("Ошибка, данные по отклику N$reply_id не найдены, вероятно они устарели");
        }

    }

    /**
     * @param $id
     * @return Response
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionApproveDoer($task_id, $reply_id)
    {
        $task = Tasks::findOne($task_id);
        $reply = Replies::findOne($reply_id);

        $curUserId = Yii::$app->user->id;

        if ($task && $reply && $task->client_id === $curUserId && $task->status_id = 1 && $reply->is_approved === 0 && $reply->is_denied === 0) {
            $reply->is_approved = 1;
            $task->status_id = TASK_IN_PROCESS_STATUS;
            $task->performer_id = $reply->user_id;
            $task->save(false);
            $reply->save(false);
            return $this->redirect(['tasks/view', 'id' => $task_id]);
        } else {
            throw new NotFoundHttpException("Ошибка, данные по отклику N$reply_id не найдены, вероятно они устарели");
        }
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionRefuse($id)
    {
        $task = Tasks::findOne($id);
        $curUserId = Yii::$app->user->id;
        $user_settings = UserSettings::find()->where(['user_id' => $curUserId])->one();

        if ($user_settings && $task && $curUserId) {
            $task->status_id = 5;
            $user_settings->refused_counter++;
            $user_settings->save(false);
        }
        $task->update(false);

        return $this->redirect(['tasks/view', 'id' => $id]);
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionCancel($id)
    {
        $task = Tasks::findOne($id);
        if ($task) {
            $task->status_id = 2;
            $task->update(false);
            return $this->redirect(['tasks/view', 'id' => $id]);
        } else {
            throw new NotFoundHttpException("Техническая ошибка");
        }

    }


    /**
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionComplete()
    {
        if (Yii::$app->request->getIsPost()) {
            $myOpinion = new Opinions();
            $taskId = Yii::$app->request->post('taskId');
            $task = Tasks::findOne($taskId);
            $curUserId = Yii::$app->user->id;
            if ($task->client_id === $curUserId && $task->status->id === TASK_IN_PROCESS_STATUS) {
                $myOpinion->load(Yii::$app->request->post());
            }
            if ($task->client_id === $curUserId && $task->status->id === TASK_IN_PROCESS_STATUS) {
                $task->status_id = 3;
                $task->update(false);
            }
            $myOpinion->owner_id = Yii::$app->user->id;
            $myOpinion->performer_id = $task->performer_id;
            if ($myOpinion->validate()) {
                $myOpinion->save(false);
            }
            return $this->redirect(['tasks/view', 'id' => $taskId]);
        } else {
            throw new NotFoundHttpException("Техническая ошибка");
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $curUserId = Yii::$app->user->id;

        $task = Tasks::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Задача с ID $id не найдена");
        }

        $taskFunctional = [
            'iAmClient' => boolval($curUserId === $task->client_id),
            'iHaveRepliedYet' => boolval(Replies::findOne(['user_id' => $curUserId, 'task_id' => $id]))
        ];

        $replies = Replies::find()->where(['task_id' => $task->id])->orderBy(['dt_add' => SORT_DESC])->all();

        $files = $task->getFiles()->select('name, path')->asArray()->all();
        if (!empty($files)) {
            foreach ($files as $key => &$file) {
                $filePath = Yii::getAlias('@webroot' . $file['path']);
                if (fileExists($filePath)) {
                    $file['size'] = MyHelper::FileSizeConvert(filesize($filePath));
                } else {
                    unset($files[$key]);
                }
            }
        }

        return $this->render('view',
            [
                'task' => $task,
                'replies' => $replies,
                'files' => $files,
                'taskFun' => $taskFunctional,
                'myReply' => new Replies(),
                'opinion' => new Opinions(),
            ]);
    }
}
