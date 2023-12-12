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

MaskAssets::register($this);

Yii::$app->formatter->locale = 'ru-RU';
$user = Yii::$app->user->getIdentity();
$this->title = "Изменить мои данные";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <ul class="side-menu-list">
        <li class="side-menu-item side-menu-item--active">
            <a class="link link--nav">Мой профиль</a>
        </li>
        <li class="side-menu-item">
            <?= \yii\helpers\Html::a('Безопасность', '/users/safe/',
                ['class' => 'link link--nav']) ?>
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
    <h3 class="head-main head-regular">Мой профиль</h3>
    <div class="photo-editing">
        <div>
            <p class="form-label">Аватар</p>
            <img class="avatar-preview" src="<?= $model->avatar_path; ?>"
                 width="83" height="83">
            <?= $form->field($model, 'avatar', ['template' => '{input}'])->fileInput(
                [
                    'class' => 'hidden',
                    'id' => 'button-input',
                    'accept' => 'image/jpeg, image/png',
                ]
            )->label(false) ?>
        </div>
        <?= Html::label('Сменить аватар', 'button-input', ['class' => 'button button--black']) ?>
    </div>
    <?php
    echo $form->field($model, 'name', [
        'template' => "<label class='control-label'>" . $model->getAttributeLabel('name') . "</label>" . "{input}\n{error}",
    ])->textInput([
    ]); ?>
    <div class="half-wrapper">
        <?php
        echo $form->field($model, 'email', [
            'template' => "<label class='control-label'>" . $model->getAttributeLabel('email') . "</label>" . "{input}\n{error}",
        ])->textInput([
        ]);

        echo $form->field($model, 'birthday')->widget(DatePicker::class, [
            'options' => ['placeholder' => 'гггг.мм.дд'],
            'language' => 'ru',
            'dateFormat' => 'php:Y.m.d',
            'clientOptions' => [
                'maxDate' => date('Y.m.d', strtotime('-14 years')), // Максимальная дата (текущая дата)
                'minDate' => date('Y.m.d', strtotime('-120 years')),
            ],
        ]);
        ?>
    </div>
    <div class="half-wrapper">
        <?php
        echo $form->field($model, 'phone', [
            'template' => "<label class='control-label'>" . $model->getAttributeLabel('phone') . "</label>" . "{input}\n{error}",
        ])->textInput([
        ]);
        echo $form->field($model, 'messenger', [
            'template' => "<label class='control-label'>" . $model->getAttributeLabel('messenger') . "</label>" . "{input}\n{error}",
        ])->textInput([
        ]);
        ?>
    </div>
    <?php
    echo $form->field($model, 'about', [
        'template' => "<label class='control-label'>" . $model->getAttributeLabel('about') . "</label>" . "{input}\n{error}",
    ])->textarea();

    echo $form->field($model, 'categories')->checkboxList($model->getCategories(), [
        'item' => function ($index, $label, $name, $checked, $value) {
            $id = $name . '-' . $index;
            return "
                <label class='control-label' for='{$id}'>
                    <input type='checkbox' id='{$id}' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . ">
                    {$label}
                </label>";
        },
        'class' => 'checkbox-profile', // Add a class to the container div
        'tag' => 'div', // Specify the container tag
        'encode' => false, // Do not HTML-encode the labels
        'itemOptions' => ['class' => 'checkbox-item'], // Add a class to each checkbox label
    ])->label();
    echo Html::submitButton('Сохранить', ['class' => 'button button--blue']);
    ?>
    <?php $form::end(); ?>
</div>