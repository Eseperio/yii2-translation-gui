<?php

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */

use yii\helpers\Html;
use eseperio\translatemanager\helpers\Language;
use eseperio\translatemanager\models\Language as Lang;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


/* @var $this \yii\web\View */
/* @var $language_id string */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \eseperio\translatemanager\models\searches\LanguageSourceSearch */
/* @var $searchEmptyCommand string */

$this->title = Yii::t('language', 'Translation into {language_id}', ['language_id' => $language_id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@eseperio/translatemanager/views/layouts/main.php'); ?>

<?= Html::hiddenInput('language_id', $language_id, ['id' => 'language_id']); ?>
<div id="translates" class="<?= $language_id ?>">
    <?php
    Pjax::begin([
        'id' => 'translates',
    ]);
    ?>

    <div class="panel">
        <div class="panel-body">
            <p><?= Yii::t('language', 'You can choose a different base language to help you understand the message') ?></p>
            <?php
            $form = ActiveForm::begin([
                'method' => 'get',
                'id' => 'search-form',
                'action' => ['translate'],
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
            ]);
            echo $form->field($searchModel, 'source')->dropDownList(['' => Yii::t('language', 'Original')] + Lang::getLanguageNames(true))->label(Yii::t('language', 'Source language'));
            ActiveForm::end();
            ?>
        </div>
    </div>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'format' => 'raw',
                'attribute' => 'message',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'message'],
                'label' => Yii::t('language', 'Source'),
                'content' => function ($data) {
                    return Html::textarea('LanguageSource[' . $data->id . ']', $data->source, [
                        'class' => 'form-control source', 'readonly' => 'readonly'
                    ]);
                },
            ],
            [
                'format' => 'raw',
                'content' => function ($data) {
                    return Html::button(Html::tag('i','',['class'=>'fa fa-arrow-right']), [
                        'type' => 'button', 'data-url' => Yii::$app->urlManager->createUrl('/translatemanager/language/auto-translate'),
                        'data-id' => $data->id,
                        'class' => 'btn btn-sm btn-primary auto-translate-button'
                    ]);
                },
                'contentOptions' => ['style' => 'width: 30px; text-align: center;'],
            ],
            [
                'format' => 'raw',
                'attribute' => 'translation',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'id' => 'translation',
                    'placeholder' => $searchEmptyCommand ? Yii::t('language', 'Enter "{command}" to search for empty translations.', ['command' => $searchEmptyCommand]) : '',
                ],
                'label' => Yii::t('language', 'Translation'),
                'content' => function ($data) {
                    return Html::textarea('LanguageTranslate[' . $data->id . ']', $data->translation, ['class' => 'form-control translation', 'data-id' => $data->id, 'tabindex' => $data->id]);
                },
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('language', 'Action'),
                'content' => function ($data) {
                    return Html::button(Yii::t('language', 'Save'), ['type' => 'button', 'data-url' => Yii::$app->urlManager->createUrl('/translatemanager/language/save'), 'data-id' => $data->id, 'class' => 'btn btn-success']);
                }
            ],
            [
                'format' => 'raw',
                'filter' => Language::getCategories(),
                'attribute' => 'category',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'category'],
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>
<?php $this->endContent(); ?>
