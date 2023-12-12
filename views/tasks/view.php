<?php
setlocale(LC_ALL, 'ru_RU.utf8');

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\TasksFilter $model */

/** @var app\models\Tasks $task */
/** @var app\models\Replies $myReply */
/** @var app\models\Opinions $opinion */


/** @var $taskFun */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;
use borpheus\logic\UserRating;
use function morphos\Russian\pluralize;
use borpheus\utils\MyHelper;
use borpheus\utils\MyGeo;

$user = Yii::$app->user->getIdentity();
Yii::$app->formatter->locale = 'ru-RU';
$this->title = 'Задача';
$this->registerJsFile('/js/task.js');
$this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=' . $_ENV['YANDEX_JS_API_KEY'] . '&suggest_apikey=' . $_ENV['YANDEX_SUGGEST_API_KEY'],
    ['position' => yii\web\View::POS_HEAD]);
?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= $task->name ?></h3>
        <p class="price price--big"><?= $task->budget ?> ₽</p>
    </div>
    <p class="task-description">
        <?= $task->description; ?></p>
    <?php foreach (MyHelper::getAvaliableButtons($task, $user) as $button): ?>
        <?= $button; ?>
    <?php endforeach; ?>
    <?php
    if (preg_match("/^[0-9. ]+$/", $task->location)): ?>
        <div class="task-map">
            <div id="map" class="map" style="width: 725px;height: 346px;">
            </div>
            <?php
            $coordinates = explode(' ', $task->location);
            $inf = MyGeo::getInfo($task->location);
            ?>
            <?php if ($inf['success']): ?>
                <?php if ($inf['info']['city']): ?>
                    <p class="map-address town"><?php echo $inf['info']['city']; ?></p>
                <?php endif; ?>
                <?php if ($inf['info']['address']): ?>
                    <p class="map-address full-address"><?php echo $inf['info']['address']; ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <script type="text/javascript">
            ymaps.ready(init);

            function init() {
                var myMap = new ymaps.Map("map", {
                    center: [<?=$coordinates[1] ?>, <?=$coordinates[0] ?>],
                    zoom: 14
                });
            }
        </script>
    <?php
    endif;
    ?>
    <?php if (($taskFun['iAmClient'] || $taskFun['iHaveRepliedYet']) && !empty($replies)): ?>
        <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($replies as $reply):
            if ($taskFun['iAmClient'] || $reply->user_id === $user->id):?>
                <div class="response-card">
                    <?php $userPath = $reply->user->avatarPath; ?>
                    <img class="customer-photo" src="<?= $userPath ?: Url::to('@web/img/no_photo.jpg'); ?>" width="146" height="156"
                         alt="Фото заказчиков">
                    <div class="feedback-wrapper">
                        <a href="/users/view/<?php echo $reply->user->id; ?>"
                           class="link link--block link--big"><?php echo $reply->user->name; ?></a>
                        <div class="response-wrapper">
                            <?php
                            $rating = UserRating::getUserRating($reply->user_id);
                            if ($rating > 0):?>
                                <div class="stars-rating small">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <span
                                            <?php if ($i < $rating): ?>class="fill-star" <?php endif; ?>>&nbsp;</span>
                                    <?php
                                    endfor; ?>
                                </div>
                            <?php endif; ?>
                            <p class="reviews"><?php
                                $replyCount = $reply->user->getOpinionsAboutMe()->count();
                                echo pluralize($replyCount, 'отзыв');
                                ?></p>
                        </div>
                        <p class="response-message">
                            <?php echo $reply->description; ?>
                        </p>
                    </div>
                    <div class="feedback-wrapper">
                        <p class="info-text"><?php echo Yii::$app->formatter->asRelativeTime($reply->dt_add); ?></p>
                        <p class="price price--small"><?php echo $reply->sum; ?> ₽</p>
                    </div>
                    <?php if ($taskFun['iAmClient'] && $reply->is_denied === 0 && $reply->is_approved === 0 && is_null($task->performer_id)): ?>
                        <div class="button-popup">
                            <?= \yii\helpers\Html::a('Принять',
                                ['tasks/approve-doer', 'task_id' => $task->id, 'reply_id' => $reply->id],
                                ['data-method' => 'post', 'class' => 'button button--blue button--small']) ?>
                            <?= \yii\helpers\Html::a('Отказать',
                                ['tasks/deny-doer', 'task_id' => $task->id, 'reply_id' => $reply->id],
                                ['data-method' => 'post', 'class' => 'button button--orange button--small']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
            endif;
        endforeach;
    endif; ?>
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <?php if ($task->category->name): ?>
                <dt>Категория</dt>
                <dd><?php echo $task->category->name; ?></dd>
            <?php endif; ?>
            <dt>Дата публикации</dt>
            <dd><?php echo Yii::$app->formatter->asRelativeTime($task->dt_add); ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?php echo Yii::$app->formatter->asDate($task->expire_dt, 'd MMMM, H:mm'); ?></dd>
            <?php if ($task->status->name_display): ?>
                <dt>Статус</dt>
                <dd><?php echo $task->status->name_display; ?></dd>
            <?php endif; ?>
        </dl>
    </div>
    <?php
    if (!empty($files)): ?>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <?php foreach ($files as $file): ?>
                    <li class="enumeration-item">
                        <a href="<?php echo $file['path']; ?>" target="_blank"
                           class="link link--block link--clip"><?php echo $file['name']; ?></a>
                        <p class="file-size"><?php echo $file['size']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php if (MyHelper::userCanRefuse($task, $user)): ?>
    <section class="pop-up pop-up--refusal pop-up--close">
        <div class="pop-up--wrapper">
            <h4>Отказ от задания</h4>
            <p class="pop-up-text">
                <b>Внимание!</b><br>
                Вы собираетесь отказаться от выполнения этого задания.<br>
                Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
            </p>
            <?= \yii\helpers\Html::a('Отказаться', ['tasks/refuse', 'id' => $task->id],
                ['data-method' => 'post', 'class' => 'button button--pop-up button--orange']) ?>
            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if (MyHelper::userCanComplete($task, $user)): ?>
    <section class="pop-up pop-up--completion pop-up--close">
        <div class="pop-up--wrapper">
            <h4>Завершение задания</h4>
            <p class="pop-up-text">
                Вы собираетесь отметить это задание как выполненное.
                Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
            </p>
            <div class="completion-form pop-up--form regular-form">
                <?php
                $opinionForm = ActiveForm::begin(
                    [
                        'action' => ['tasks/complete'],
                        'method' => 'post',
                        'options' => ['class' => 'form-opinion'],
                    ]);

                echo $opinionForm->field($opinion, 'description')->textarea()->label('Ваш комментарий');
                echo Html::hiddenInput('taskId', $task->id);
                echo $opinionForm->field($opinion, 'rate', [
                    'template' => '<label class="completion-head control-label">Оценка работы' . "{input}" . '</label><div class="stars-rating big active-stars">
                <span data-val="1">&nbsp;</span>
                <span data-val="2">&nbsp;</span>
                <span data-val="3">&nbsp;</span>
                <span data-val="4">&nbsp;</span>
                <span data-val="5">&nbsp;</span>
            </div>' . "\n{error}"
                ])->hiddenInput()->label(false);
                ?>
                <?php
                echo Html::submitButton('Завершить', ['class' => 'button button--pop-up button--blue']);
                ?>
                <?php $opinionForm::end(); ?>
            </div>
            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if (MyHelper::userCanRespond($task, $user)): ?>
    <section class="pop-up pop-up--act_response pop-up--close">
        <div class="pop-up--wrapper">
            <h4>Добавление отклика к заданию</h4>
            <p class="pop-up-text">
                Вы собираетесь оставить свой отклик к этому заданию.
                Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
            </p>
            <div class="addition-form pop-up--form regular-form">
                <?php
                $replyForm = ActiveForm::begin(
                    [
                        'action' => ['tasks/new-reply'],
                        'method' => 'post',
                        'options' => ['class' => 'form-reply'],
                    ]);

                echo $replyForm->field($myReply, 'description')->textarea()->label('Ваш комментарий');
                echo $replyForm->field($myReply, 'sum')->textInput();
                echo Html::hiddenInput('taskId', $task->id);
                echo Html::submitButton('Завершить', ['class' => 'button button--pop-up button--blue']);
                ?>
                <?php $replyForm::end(); ?>
            </div>
            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
            </div>
        </div>
    </section>
<?php endif; ?>
<div class="overlay"></div>