<?php

namespace app\controllers;

use app\models\Auth;
use app\models\Replies;
use app\models\RoleForm;
use app\models\UserSettings;
use Yii;
use yii\db\Migration;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Role;
use app\models\Tasks;
use app\models\Users;
use borpheus\utils\MyHelper;
use Faker\Factory;
use app\models\Cities;
use yii\widgets\ActiveForm;
use GuzzleHttp\Client;
use yii\authclient\clients\VKontakte;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use function PHPUnit\Framework\throwException;
use yii\base\Exception;
use DateTime;
use yii\web\ForbiddenHttpException;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'choose-role'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['choose-role'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity && is_null(Yii::$app->user->identity->role_id);
                        }
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    if ($action->id == 'index') {
                        Yii::$app->session->setFlash('info', 'You are already logged in, no need to reset password');
                        return $action->controller->redirect('/tasks/');
                    }
                    if ($action->id == 'choose-role') {
                        return $action->controller->redirect('/tasks/');
                    }
                },
            ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        $this->layout = 'guest';

        $loginForm = new LoginForm();
        return $this->render('index', [
            'model' => $loginForm,
        ]);
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionGetAuthPopup()
    {
        try {
            $oauthClient = new VKontakte([
                'clientId' => $_ENV['VK_CLIENT_ID'],
                'clientSecret' => $_ENV['VK_CLIENT_SECRET'],
            ]);

            // Build authorization URL
            $url = $oauthClient->buildAuthUrl([
                'display' => 'popup',
                'redirect_uri' => 'https://taskforce.local/site/vk-auth'
            ]);

            // Send GET request to the authorization URL
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            Yii::$app->getResponse()->redirect($url);

        } catch (ClientException $e) {
            // Handle Guzzle ClientException
            $response = $e->getResponse();
            $errorBody = $response->getBody()->getContents();

            $errorMessage = "Error Body: $errorBody";
        } catch (\Exception $e) {
            // Handle other exceptions
            $errorMessage = $e->getMessage();
            echo $errorMessage;
        }
    }


    /**
     * @return array|string|Response
     */
    public function actionChooseRole()
    {
        $form_model = new RoleForm();

        if (Yii::$app->request->getIsPost()) {
            $form_model->load(\Yii::$app->request->post());
            if (\Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($form_model);
            }

            if ($form_model->validate()) {
                $role_id = $form_model->role_id;
                $user = Users::findOne(Yii::$app->user->id);
                $user->role_id = $role_id;
                $user->save(false);
                return $this->goHome();
            }
        }

        return $this->render('choose-role', [
            'model' => $form_model,
        ]);
    }


    /**
     * @return string|Response
     */
    public function actionVkAuth()
    {
        $this->layout = 'anon';
        $oauthClient = new VKontakte([
            'clientId' => $_ENV['VK_CLIENT_ID'],
            'clientSecret' => $_ENV['VK_CLIENT_SECRET'],
        ]);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $code = Yii::$app->getRequest()->get('code');
            $oauthClient->fetchAccessToken($code);

            $userInfo = $oauthClient->api('users.get', 'GET', [
                'fields' => 'id,first_name,last_name,bdate,city,photo,email',
            ]);


            if (is_array($userInfo)) {

                $hasAccountYet = Auth::find()->where(['vk_id' => $userInfo['response'][0]['id']])->one();

                if ($hasAccountYet !== null) {
                    $user = Users::findOne($hasAccountYet->user_id);
                    Yii::$app->user->login($user);
                    $transaction->commit();
                    return $this->redirect(['tasks/']);
                }

                $city_id = 0;
                $newUser = new Users();
                if (isset($userInfo['response'][0]['city'])) {
                    $userCity = $userInfo['response'][0]['city']['title'];
                    $city = Cities::find()->where(['name' => $userCity])->one();
                    if ($city !== null) {
                        $city_id = $city->id;

                    } elseif ($userCity !== '') {
                        $newCity = new Cities();
                        $newCity->attributes = [
                            'name' => $userCity
                        ];
                        $newCity->save(false);
                        $city_id = $newCity->id;
                    }
                }


                $password = md5(uniqid(rand(), true));
                $newUser->attributes = [
                    'name' => $userInfo['response'][0]['first_name'],
                    'city_id' => $city_id,
                    'password' => $password
                ];
                if ($newUser->save(false)) {
                    $newUserSettings = new UserSettings();

                    $prop = ['user_id' => $newUser->id];
                    if ($userInfo['response'][0]['bdate']) {
                        $dateTime = DateTime::createFromFormat('j.n.Y', $userInfo['response'][0]['bdate']);
                        $newFormat = $dateTime->format('Y-m-d');
                        $prop['bd'] = $newFormat;
                    }
                    $newUserSettings->attributes = $prop;

                    if ($newUserSettings->save(false)) {
                        $newAuth = new Auth();
                        $newAuth->attributes = [
                            'vk_id' => $userInfo['response'][0]['id'],
                            'user_id' => $newUser->id
                        ];
                        if (!$newAuth->save(false)) {
                            throw new \Exception("Error saving Auth");
                        }
                        Yii::$app->user->login($newUser);
                    } else {
                        throw new \Exception("Error saving UserSettings");
                    }
                } else {
                    throw new \Exception("Error saving User");
                }
            }
            $transaction->commit();

            return $this->redirect(['site/choose-role']);

        } catch (\Exception $e) {
            $transaction->rollBack();
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


    /**
     * @return array|void|Response
     */
    public function actionAuth()
    {
        $loginForm = new LoginForm();
        if (\Yii::$app->request->getIsPost()) {
            $loginForm->load(\Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                \Yii::$app->user->login($loginForm->getUser());
                return $this->redirect(['tasks/']);
            }
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
