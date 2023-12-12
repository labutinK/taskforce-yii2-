<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use app\models\Users;

AppAsset::register($this);

$userName = Yii::$app->user->identity->name;
$userPath = Users::findOne(Yii::$app->user->id)->avatarPath;

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header class="page-header">
    <nav class="main-nav">
        <a href='<?php echo Yii::$app->homeUrl; ?>' class="header-logo">
            <img class="logo-image" src="<?= Yii::$app->urlManager->createUrl(['/img/logotype.png']) ?>" width=227
                 height=60 alt="taskforce">
        </a>
        <div class="nav-wrapper">
            <ul class="nav-list">
                <?php if (!is_null(Yii::$app->user->identity->role_id)): ?>
                    <li class="list-item ">
                        <?= \yii\helpers\Html::a('Новое', '/tasks/',
                            ['class' => 'link link--nav']) ?>
                    </li>
                    <li class="list-item">
                        <?= \yii\helpers\Html::a('Мои задания', ['tasks/my-tasks'],
                            ['data-method' => 'post', 'class' => 'link link--nav']) ?>
                    </li>
                    <?php if (Users::isCustomer()): ?>
                        <li class="list-item">
                            <?= \yii\helpers\Html::a('Создать задание', ['tasks/add-task'],
                                ['class' => 'link link--nav']) ?>
                        </li>
                    <?php endif; ?>
                    <li class="list-item">
                        <?= \yii\helpers\Html::a('Настройки', ['users/edit'],
                            ['class' => 'link link--nav']) ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="user-block">
        <a href="#">
            <img class="user-photo" src="<?= $userPath ?: Url::to('@web/img/no_photo.jpg'); ?>" width="55"
                 height="55" alt="Аватар">
        </a>
        <div class="user-menu">
            <p class="user-name"><?php echo $userName; ?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <?= \yii\helpers\Html::a('Мой профиль', ['users/view', 'id' => Yii::$app->user->id],
                            ['class' => 'link']) ?>
                    </li>
                    <li class="menu-item">
                        <?= \yii\helpers\Html::a('Настройки', ['users/edit'],
                            ['class' => 'link']) ?>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="link">Связаться с нами</a>
                    </li>
                    <li class="menu-item">
                        <?= \yii\helpers\Html::a('Выход из системы', ['site/logout'],
                            ['data-method' => 'post', 'class' => 'link']) ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<main id="main" class="main-content container" role="main">
    <?= $content ?>
</main>

<footer id="footer">
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
