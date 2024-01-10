<?php

use eseperio\proshop\common\helpers\Url;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $language array */
/* @var $patterns array */

$this->title = Yii::t('translatemanager', 'Translation into {language_id}', ['language_id' => $language['language_id']]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('translatemanager', 'Translation tool'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsVar('LOCALE_IN_TRANSLATION', $language['language_id']);

$script = <<< JS
$(document).on('click', '.index-link', function(e) {
    e.preventDefault();
    var targetId = "#"+$(this).attr('href').split('#')[1];
    $('.panel-collapse').not(targetId).collapse('hide');
    $(targetId).collapse('show');
    $('html, body').animate({
        scrollTop: $(targetId).offset().top
    }, 1000);
});
JS;
$this->registerJs($script, \yii\web\View::POS_READY);
?>

<div id="translates" class="<?= $language['language_id'] ?>">
    <div class="row">
        <div class="col-md-3"><div class="box">
                <ul class="nav nav-pills nav-stacked">
                    <?php foreach ($patterns as $patternName => $categories): ?>
                        <?php $safePatternName = Inflector::slug($patternName); ?>
                        <li>
                            <a href="<?= Url::current() ?>#collapse-<?= $safePatternName ?>"
                               class="index-link"><?= $patternName ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div></div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-body">
                    <?php foreach ($patterns as $patternName => $categories): ?>
                        <?php $safePatternName = Inflector::slug($patternName); ?>
                        <div class="panel-group" id="accordion-<?= $safePatternName ?>">
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse"
                                     data-parent="#accordion-<?= $safePatternName ?>"
                                     href="#collapse-<?= $safePatternName ?>">
                                    <h2 class="panel-title">
                                        <?= $patternName ?>
                                    </h2>
                                </div>
                                <div id="collapse-<?= $safePatternName ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php foreach ($categories['messages'] as $category => $messages): ?>
                                        <?php if($category !==$patternName): ?>
                                                <h3><?= $category ?></h3>

                                        <?php endif; ?>
                                            <?php foreach ($messages as $key => $message): ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="well"><?= $key ?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <?php if(strlen($key)> 60): ?>
                                                        <textarea class="form-control" rows="3"><?= $message ?></textarea>
                                                        <?php else: ?>
                                                        <input type="text" class="form-control" value="<?= $message ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button class="btn btn-default btn-translate" data-key="<?= $key ?>" data-locale="<?= $language['language_id'] ?>"><?= Yii::t('translatemanager', 'Autotranslate') ?></button>
                                                    </div>
                                                </div>
                                                <hr>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
