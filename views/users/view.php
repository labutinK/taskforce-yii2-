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
use app\models\Users;
use yii\helpers\Url;
use borpheus\utils\MyHelper;
use borpheus\logic\UserRating;

$userPath = $user->avatarPath;
Yii::$app->formatter->locale = 'ru-RU';
$name = $user->name;
$this->title = "Профиль пользователя $name";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="left-column">
    <h3 class="head-main"><?php echo $name; ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="<?= $userPath ?: Url::to('@web/img/no_photo.jpg'); ?>" width="191" height="190"
                 alt="Фото пользователя">
            <div class="card-rate">
                <?php
                $rating = UserRating::getUserRating($user->id);
                if ($rating > 0):
                    if (round($rating) > 0):?>
                        <div class="stars-rating big">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <span
                                    <?php if ($i < round($rating)): ?>class="fill-star" <?php endif; ?>>&nbsp;</span>
                            <?php
                            endfor; ?>
                        </div>
                    <?php endif; ?>
                    <span class="current-rate"><?php echo $rating; ?></span>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($user->userSettings->about): ?>
            <p class="user-description">
                <?php echo $user->userSettings->about; ?>
            </p>
        <?php endif; ?>
    </div>
    <div class="specialization-bio">
        <?php
        $categories = $user->getUserCategoriesNames();
        if (!empty($categories)):
            ?>
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($categories as $category): ?>
                        <li class="special-item">
                            <span class="link link--regular"><?php echo $category; ?> </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info">
                <?php if ($user->city->name): ?><span
                        class="town-info"><?php echo $user->city->name; ?></span><?php endif; ?>
                <?php if ($user->userSettings->bd && $user->city->name): ?>, <?php endif; ?>
                <?php if ($user->userSettings->bd): ?>
                    <span class="age-info"><?php echo DateBack::getAge($user->userSettings->bd); ?> лет</span>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php
    $opinions = UserRating::getUserOpinions($user->id);
    if ($opinions): ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($opinions as $opinion):
            $userPath = $opinion->owner->avatarPath;
            ?>
            <div class="response-card">
                <img class="customer-photo" src="<?= $userPath ?: Url::to('@web/img/no_photo.jpg'); ?>" width="120"
                     height="127" alt="Фото заказчиков">
                <div class="feedback-wrapper">
                    <p class="feedback"><?php echo $opinion->description; ?></p>
                    <p class="task">Задание «
                        <?= \yii\helpers\Html::a($opinion->task->name, ['tasks/view', 'id' => $opinion->task->id],
                            ['class' => 'link link--small']) ?>
                        » выполнено</p>
                </div>
                <div class="feedback-wrapper">
                    <?php $rate = $opinion->rate;
                    if ($rate > 0):
                        ?>
                        <div class="stars-rating small">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <span
                                    <?php if ($i < $rate): ?>class="fill-star" <?php endif; ?>>&nbsp;</span>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                    <p class="info-text"><?php echo Yii::$app->formatter->asRelativeTime($opinion->dt_add); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <?php if ($user->role_id === 2): ?>
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                <dt>Всего заказов</dt>
                <dd>
                    <?php echo $taskDone; ?> выполнено, <?php echo $tasksFailed; ?> провалено
                </dd>
                <dt>Место в рейтинге</dt>
                <dd><?php echo UserRating::getPlaceInRating($user->id); ?> место</dd>
                <dt>Дата регистрации</dt>
                <dd><?php echo Yii::$app->formatter->asDate($user->dt_add, 'd MMMM, H:mm'); ?></dd>
                <dt>Статус</dt>
                <?php
                if (boolval($user->userSettings->opt_hide_me) === false): ?>
                    <dd>Открыт для новых заказов</dd>
                <?php else: ?>
                    <dd>Временно недоступен</dd>
                <?php endif; ?>
            </dl>
        </div>
    <?php endif; ?>
    <?php
    if (Users::canViewContacts($user)): ?>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <?php if ($user->userSettings->phone): ?>
                    <li class="enumeration-item">
                        <a target="_blank" href="tel:<?php echo $user->userSettings->phone; ?>"
                           class="link link--block link--phone"><?php echo $user->userSettings->phone; ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($user->email): ?>
                    <li class="enumeration-item">
                        <a target="_blank" href="mailto:<?php echo $user->email; ?>"
                           class="link link--block link--email"><?php echo $user->email; ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($user->userSettings->messenger): ?>
                    <li class="enumeration-item">
                        <a target="_blank" href="https://t.me/<?php echo $user->userSettings->messenger; ?>"
                           class="link link--block link--tg"><?php echo $user->userSettings->messenger; ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>

</div>
