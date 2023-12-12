<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;
use app\models\Role;

/** @var yii\web\View $this */
/** @var \app\models\LoginForm $model */

$this->title = 'Выберите роль';
?>
<div class="landing-container">
    <div class="landing-top">
        <h1>Выберите роль!.</h1>
        <div class="choose-role">
            <div class="choose-role__form">
                <?php
                $form = ActiveForm::begin([
                    'enableAjaxValidation' => 'true',
                    'action' => '',
                    'method' => 'post',
                    'options' => ['class' => 'form-role-choose']
                ]); ?>
                <div class="choose-role__options">
                    <div class="choice-role__input">
                        <?php
                        echo $form->field($model, 'role_id')->radioList(
                            [1 => "Клиент", 2 => "Исполнитель"],
                            [
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $descr = '';
                                    if ($value == 1) {
                                        $descr = '<div class="choice-role__label">Зарегистрируйтесь как клиент, чтобы размещаться задачи!</div>';
                                    } elseif ($value == 2) {
                                        $descr = '<div class="choice-role__label">Зарегистрируйтесь как исполнитель, чтобы выполнять задачи!</div>';
                                    }

                                    return "
 <div class='choice-role'>
<label>$label <input type='radio' name='" . $name . "' value='" . $value . "'> 
" . $descr . '</label></div>';
                                }
                            ]
                        )->label(false);

                        ?>
                    </div>
                </div>
                <?php
                echo Html::submitButton('Выбрать', ['class' => 'button']);
                $form::end(); ?>
            </div>
        </div>
    </div>
</div>

