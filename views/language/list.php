<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
use yii\grid\GridView;
use yii\helpers\Html;
use eseperio\translatemanager\models\Language;
use yii\widgets\Pjax;
use eseperio\translatemanager\bundles\BulkTranslationPluginAsset;

/* @var $this \yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel eseperio\translatemanager\models\searches\LanguageSearch */

BulkTranslationPluginAsset::register($this);

$this->title = Yii::t('language', 'List of languages');
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="languages">

    <?php
    Pjax::begin([
        'id' => 'languages',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'language_id',
            'name_ascii',
            [
                'format' => 'raw',
                'filter' => Language::getStatusNames(),
                'attribute' => 'status',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'status'],
                'label' => Yii::t('language', 'Status'),
                'content' => function ($language) {
                    return Html::activeDropDownList($language, 'status', Language::getStatusNames(), ['class' => 'status', 'id' => $language->language_id, 'data-url' => Yii::$app->urlManager->createUrl('/translatemanager/language/change-status')]);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => Yii::t('language', 'Statistic'),
                'content' => function ($language) {
                    return '<span class="statistic"><span style="width:' . $language->gridStatistic . '%"></span><i>' . $language->gridStatistic . '%</i></span>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {translate} {delete} {bulktranslation}',
                'buttons' => [
                    'translate' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-list"></span>', ['language/translate', 'language_id' => $model->language_id], [
                            'title' => Yii::t('language', 'Translate'),
                            'data-pjax' => '0',
                        ]);
                    },
                    'bulktranslation' => function ($url, $model, $key) {
                        return Html::button('BT', ['type' => 'button', 'data-id' => $model->language_id, 'class' => 'btn btn-sm btn-primary bulk-translation', 'data-url' => '/manager/translatemanager/language/bulk-auto-translate',
                            'title' => Yii::t('language', 'Bulk language translation'),
                        ]);
                    },
                ],
            ],
        ],
    ]);
    Pjax::end();
    ?>

    <?php
    yii\bootstrap\Modal::begin([
        'header' => '<h2>'. Yii::t('language', 'Translate the entire language '). '<span id="modal_languaje_id"></span></h2>',
        'id' => 'bulk-translation-modal',
        'size' => 'modal-md',
    ]);
    ?>

    <?php
    $css = <<<CSS
    /* Aquí va tu código CSS */
    span#loadingSpinner {
        display: none;
    }
    
    span#loadingSpinner i::before {
        font-weight: 600;
        color: #e30057;
        font-size: 15px;
    }
    CSS;
    $this->registerCss($css);
    ?>

    <div id="bulk-translation-content">
        <div class="row">
            <div class="col-md-8"><?= Yii::t('language','The number of characters to be translated is ')?></div>
            <div class="col-md-4" id="modal_total_charts"></div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div><?= Yii::t('language', 'Are you sure you can translate the whole language?')?></div>
            </div>
        </div>
        <br>
        <?php
            echo Html::button('Confirm translation', ['type' => 'button', 'class' => 'btn btn-sm btn-primary', 'data-url' => '/manager/translatemanager/language/bulk-auto-translate', 'id' => 'bulk-translation-confirm']);
        ?>
        <span id="loadingSpinner">
            <i class="fal fa-spinner fa-spin"></i>
        </span>
    </div>

    <?php
    yii\bootstrap\Modal::end();
    ?>
</div>
