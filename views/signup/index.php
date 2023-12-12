<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\TasksFilter $model */

/** @var app\models\Tasks $tasks */

/** @var $cities */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;
use app\models\Cities;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'options' => []
        ]); ?>
        <h3 class="head-main head-task">Регистрация нового пользователя</h3>
        <?= $form->field($model, 'name', [
            'template' => "<label class='control-label'>" . $model->getAttributeLabel('name') . "</label>" . "{input}\n{error}",
        ])->textInput([
        ]); ?>
        <div class="half-wrapper">
            <?= $form->field($model, 'email', [
                'template' => "<label class='control-label'>" . $model->getAttributeLabel('email') . "</label>" . "{input}\n{error}",
            ])->textInput([
            ]); ?>
            <?php
            echo $form->field($model, 'city_id')->dropDownList(
                array_filter($cities),
            );
            ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($model, 'password', [
                'template' => "<label class='control-label'>" . $model->getAttributeLabel('password') . "</label>" . "{input}\n{error}",
            ])->passwordInput([
            ]); ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($model, 'password_retype', [
                'template' => "<label class='control-label'>" . $model->getAttributeLabel('password_retype') . "</label>" . "{input}\n{error}",
            ])->passwordInput([
            ]); ?>
        </div>
        <?= $form->field($model, 'is_doer')->checkbox(
            ['value' => true]
        )->label(false);
        echo Html::submitButton('Искать', ['class' => 'button button--blue']);
        ActiveForm::end(); ?>
    </div>
</div>