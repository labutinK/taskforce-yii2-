<?php
setlocale(LC_ALL, 'ru_RU.utf8');

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\Tasks $model */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\MaskedInput;
use app\assets\MaskAssets;
use yii\helpers\Url;

MaskAssets::register($this);

Yii::$app->formatter->locale = 'ru-RU';
$this->title = 'Новая задача';
$geoUrl = Yii::$app->urlManager->createUrl(['/geo/get-coordinates']);
$this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=' . $_ENV['YANDEX_JS_API_KEY'] . '&suggest_apikey=' . $_ENV['YANDEX_SUGGEST_API_KEY'],
    ['position' => yii\web\View::POS_HEAD]);
?>
<?php
$this->registerJs(<<<JS

    var geoUrl = '{$geoUrl}';

    function getCoordinates (addr) {
        $('.regular-form').addClass('area-darken');
            $.ajax({
                url: geoUrl,
                dataType: 'json',
                data: {
                    address: addr
                },
                success: function(data) {
                    if(data['success'] === true){
                        $('#location').val(data['coordinates']['points']);
                    }
                    else{
                        $('#location').val('');
                    }
                    $('.regular-form').removeClass('area-darken');
                }
            });
        }

       ymaps.ready(function () {
        var suggestView = new ymaps.SuggestView('location-choose');

        suggestView.events.add('select', (event) => {
            getCoordinates(event.get('item').displayName);
        });
        
         $('#location-choose').on('change', () => {
             getCoordinates($('#location-choose').val());
         });
    });
JS, \yii\web\View::POS_READY);
?>
<div class="add-task-form regular-form">
    <?php
    $form = ActiveForm::begin([
        'id' => 'add-task',
        'method' => 'POST',
        'options' => []
    ]); ?>
    <h3 class="head-main head-main">Публикация нового задания</h3>
    <?php
    echo $form->field($model, 'name', [
        'template' => "<div class='form-group'>{label}{input}\n{error}</div>",
    ])->label('Опишите суть работы');
    echo $form->field($model, 'description', [
        'template' => "<div class='form-group'>{label}{input}\n{error}</div>",
    ])->textarea();

    echo $form->field($model, 'category_id')->dropDownList($model->getCategories()); ?>
    <div class="half-wrapper">
        <?php
        echo $form->field($model, 'budget', [
            'template' => "{label}<div class='budget-icon'>{input}</div>\n{error}",
            'inputOptions' => [
                'class' => '',
            ],
        ]);
        echo $form->field($model, 'expire_dt')->widget(DatePicker::class, [
            'options' => ['placeholder' => 'гггг.мм.дд'],
            'language' => 'ru',
            'dateFormat' => 'yyyy.MM.dd',
            'clientOptions' => [
                'minDate' => 'new Date()', // Set the minimum date to today
            ],
        ]);
        ?>
    </div>
    <div class="form-group form-group--location">
        <label class="control-label" for="location-choose">Локация</label>
        <input class="location-icon" id="location-choose" type="text">
    </div>
    <?php
    echo $form->field($model, 'location', [
        'template' => "{input}",
    ])->hiddenInput(['id' => 'location'])->label(false);
    echo $form->field($model, 'files[]',
        [
            'template' => "<label class='new-file'>Добавить файлы{input}</label>"
        ])->fileInput([
        'accept' => 'application/pdf, application/msword,  .docx, .txt', // Допустимые форматы файлов
        'multiple' => true,
    ]);

    echo Html::submitButton('Опубликовать', ['class' => 'button button--blue']);
    ?>
    <?php $form::end(); ?>
</div>