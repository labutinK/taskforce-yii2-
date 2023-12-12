<?php
// _list_item.php

/** @var \app\models\Tasks $model */

use yii\helpers\Html;
use yii\helpers\Url;
use borpheus\utils\MyGeo;

?>
<div class="task-card">
    <div class="header-task">
        <?= \yii\helpers\Html::a($model['name'], ['tasks/view', 'id' => $model['id']],
            ['class' => 'link link--block link--big']); ?>
        <p class="price price--task"><?php echo $model['budget']; ?>₽</p>
    </div>
    <p class="info-text"><?php echo Yii::$app->formatter->asRelativeTime($model['dt_add']); ?></p>
    <p class="task-text"><?php echo $model['description']; ?></p>
    <div class="footer-task">
        <?php if (preg_match("/^[0-9. ]+$/", $model['location'])): ?>
            <?php
            $inf = MyGeo::getInfo($model['location']);
            ?>
            <?php if ($inf['success']): ?>
                <?php if ($inf['info']['address']): ?>
                    <p class="info-text town-text"><?php echo $inf['info']['address']; ?></p>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <p class="info-text category-text"><?php echo $model['category_name']; ?></p>
        <?= \yii\helpers\Html::a('Смотреть Задание', ['tasks/view', 'id' => $model['id']],
            ['class' => 'button button--black']); ?>
    </div>
</div>