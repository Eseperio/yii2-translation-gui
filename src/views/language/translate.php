<?php

use yii\helpers\Html;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $language array */
/* @var $patterns array */

$this->title = Yii::t('translatemanager', 'Translation into {language_id}', ['language_id' => $language['language_id']]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('translatemanager', 'Translation tool'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsVar('LOCALE_IN_TRANSLATION', $language['language_id']);

// init tabs
$js = <<<JS
    $('#translates a.index-link').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#translates a.index-link:first').tab('show');
JS;
$this->registerJs($js);
?>

<div id="translates" class="<?= $language['language_id'] ?>">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-body">
                <?= Html::ul($patterns, [
                    'item' => function ($item, $index) {
                        $hash = '#group-' . Inflector::slug($index);
                        $link = \yii\helpers\Url::current() . $hash;
                        return Html::tag('li', Html::a($index, $link, [
                            'class' => 'index-link'
                        ]));
                    },
                    'class' => 'nav nav-pills nav-stacked'
                ]) ?>
            </div>
        </div>
        <div class="col-md-9 tab-content">

            <?php foreach ($patterns as $pattern => $details): ?>
                <div class="panel tab-pane" role="tabpanel" id="group-<?= \yii\helpers\Inflector::slug($pattern) ?>">
                    <div class="panel-heading">
                        <h2 class="panel-title"><?= $pattern ?></h2>
                    </div>
                    <div class="panel-body">
                        <?= $this->render('partials/translate_pane', [
                            'details' => $details,
                            'pattern' => $pattern,
                            'language' => $language,
                        ]);
                        ?>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>
</div>
