<?php
setlocale(LC_ALL, 'ru_RU.utf8');

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\TasksFilter $model */

/** @var app\models\Users $user */
/** @var $taskDone */

/** @var $tasksFailed */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;
use borpheus\utils\DateBack;
use borpheus\utils\MyHelper;
use yii\jui\DatePicker;
use app\assets\MaskAssets;
use app\models\Users;

MaskAssets::register($this);

Yii::$app->formatter->locale = 'ru-RU';
$user = Yii::$app->user->getIdentity();
$this->title = "Безопасность";
$this->params['breadcrumbs'][] = $this->title;
if (!empty($errors)) {
    MyHelper::pre($errors);
}
?>
<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <ul class="side-menu-list">
        <li class="side-menu-item">
            <?= \yii\helpers\Html::a('Мой профиль', '/users/edit/',
                ['class' => 'link link--nav']) ?>
        </li>
        <li class="side-menu-item side-menu-item--active">
            <a class="link link--nav">Безопасность</a>
        </li>
    </ul>
</div>
<div class="my-profile-form">
    <?php
    $form = ActiveForm::begin([
        'id' => 'edit-profile',
        'method' => 'POST',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>
    <h3 class="head-main head-regular">Настройки безопасности</h3>
    <?php if (!Users::userFromSocialNetworks()): ?>
        <?php echo $form->field($model, 'old_password', [
            'template' => "<label class='control-label'>" . $model->getAttributeLabel('old_password') . "</label>" . "{input}\n{error}",
        ])->passwordInput([
        ]);
        echo $form->field($model, 'password', [
            'template' => "<label class='control-label'>" . $model->getAttributeLabel('password') . "</label>" . "{input}\n{error}",
        ])->passwordInput([
        ]);
        echo $form->field($model, 'password_retype', [
            'template' => "<label class='control-label'>" . $model->getAttributeLabel('password_retype') . "</label>" . "{input}\n{error}",
        ])->passwordInput([
        ]);
    endif;
    echo $form->field($model, 'hide_contacts')->checkbox(['value' => 1])->label(false);
    echo Html::submitButton('Сохранить', ['class' => 'button button--blue']);
    $form::end(); ?>
</div>