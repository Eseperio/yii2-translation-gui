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
/* @var $searchModel \eseperio\translatemanager\models\searches\LanguageSearch */

BulkTranslationPluginAsset::register($this);

$this->title = Yii::t('language', 'List of languages');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $this->beginContent('@eseperio/translatemanager/views/layouts/main.php'); ?>
<div id="languages">

    <?php
    Pjax::begin([
        'id' => 'languages',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'name_ascii',
                'format' => 'raw',
                'value' => function ($language) {
                    /* @var $language Language */
                    $missing = $language->missingTranslationNb;
                    $html = Html::tag('strong', $language->name_ascii) . ' ' . Html::tag('i', $language->language_id);

                    if ($missing) {
                        $html = Html::tag('i', '', [
                                'class' => 'fa fa-exclamation-triangle text-danger',
                                'title' => Yii::t('language', 'Missing {nb} translations', [
                                    'nb' => $missing
                                ]),
                                'data-toggle' => 'tooltip',
                            ]) . " " . $html;
                    }
                    return $html;
                },
            ],
            [
                'format' => 'raw',
                'filter' => Language::getStatusNames(),
                'attribute' => 'status',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'status'],
                'label' => Yii::t('language', 'Status'),
                'content' => function ($language) {
                    return Html::activeDropDownList($language, 'status', Language::getStatusNames(), [
                        'class' => 'status form-control',
                        'id' => $language->language_id,
                        'data-url' => Yii::$app->urlManager->createUrl('/translatemanager/language/change-status')
                    ]);
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

<!--    Create modal object with title, 1 p and 2 buttons-->
    <div class="modal fade" id="bulk-translation-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="bulk-translation-modal-label" style="display: inline-block;">
                        ¿Traducir lenguaje de forma masiva?
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bulk-translation-content">
                    <p>Language to translate: <span id="modal_languaje_id"></span></p>
                    <p>Total charts: <span id="modal_total_charts"></span></p>
                    <p>Total translations: <span id="modal_total_translations"></span></p>
                    <button id="bulk-translation-confirm" class="btn btn-primary" data-id="1"
                            data-url="/manager/translatemanager/language/bulk-auto-translate"
                            title="Bulk language translation">Bulk translation</button>
                    <span id="loadingSpinner" hidden>
                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $this->endContent(); ?>
