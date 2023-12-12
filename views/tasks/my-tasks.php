<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var yii\data\ActiveDataProvider $dataProvider */

/** @var yii\data\ActiveDataProvider $dataStatusesProvider */

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use borpheus\utils\TaskForcePagination;

$this->title = 'Мои задачи';

?>
<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <?php
    if ($dataProvider->totalCount > 0):
        echo '<div class="pagination-wrapper">';
        $class = Yii::$app->request->get('status') ? 'side-menu-item' : 'side-menu-item side-menu-item--active';
        echo Html::tag('ul',
            Html::tag(
                'li',
                Html::a(
                    "Все",
                    Url::toRoute(['tasks/my-tasks']),
                    ['class' => 'link link--nav']),
                ['class' => $class]) .
            ListView::widget([
                'dataProvider' => $dataStatusesProvider,
                'layout' => "{items}",
                'itemView' => function ($model, $key, $index, $widget) {
                    $class = intval(Yii::$app->request->get('status')) === $model->id ? 'side-menu-item side-menu-item--active' : 'side-menu-item';
                    echo Html::tag(
                        'li',
                        Html::a(
                            $model->name_display,
                            Url::toRoute(['tasks/my-tasks', 'status' => $model->id]),
                            ['class' => 'link link--nav']),
                        ['class' => $class]);
                },
            ]), ['class' => 'side-menu-list']);
        echo '</div>';
    endif;
    ?>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular">Новые задания</h3>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_list_item', ['model' => $model]);
        },
        'layout' => "{items}\n{pager}",
        'pager' => [
            'class' => TaskForcePagination::class,
        ],
    ]); ?>
</div>
