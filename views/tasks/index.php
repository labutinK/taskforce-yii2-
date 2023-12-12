<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\TasksFilter $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

/** @var app\models\Tasks $tasks */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;
use borpheus\utils\MyGeo;
use yii\widgets\ListView;
use borpheus\utils\TaskForcePagination;


$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <?php if ($dataProvider->totalCount > 0): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => function ($model, $key, $index, $widget) {
                return $this->render('list_item2', ['model' => $model]);
            },
            'layout' => "{items}\n{pager}",
            'pager' => [
                'class' => TaskForcePagination::class,
            ],
        ]); ?>

    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php
            $form = ActiveForm::begin(['method' => 'get']); ?>
            <h4 class="head-card"><?php echo $model->attributeLabels()['category_name']; ?></h4>
            <?php
            $checkboxes = $model->getCategoryNames();
            if (!empty($checkboxes)):?>
                <div class="form-group">
                    <?= $form->field($model, 'category_name')->checkboxList($checkboxes, [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '<label class="control-label">' .
                                Html::checkbox($name, $checked, ['value' => $value]) . $label .
                                '</label>';
                        },
                    ])->label(false); ?>
                </div>
            <?php endif; ?>
            <h4 class="head-card"><?php echo $model->attributeLabels()['without_customer']; ?></h4>
            <div class="form-group">
                <?php
                echo $form->field($model, 'without_customer', [
                    'template' => "<label class='control-label' for='without-customer'>{input}  Без исполнителя</label>"
                ])->checkbox([
                    'value' => 'Y',
                    'id' => "without-customer"
                ], false); ?>
            </div>
            <h4 class="head-card"><?php echo $model->attributeLabels()['period']; ?></h4>
            <?php
            echo $form->field($model, 'period')->dropDownList([
                '0' => 'Все',
                '1' => '1 час',
                '24' => '24 часов',
                '48' => '48 часов',
                '168' => 'Неделя',
            ],
            )->label(false);
            echo Html::submitButton('Искать', ['class' => 'button button--blue']);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>