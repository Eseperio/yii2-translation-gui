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
</div>
<?php $this->endContent(); ?>
