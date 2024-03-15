<?php


/* @var $this \yii\web\View */
/* @var $content string */

$menuItems = [
    ['label' => Yii::t('proshop', 'List of languages'), 'url' => ['/translatemanager/language/list']],
    ['label' => Yii::t('proshop', 'Create language'), 'url' => ['/translatemanager/language/create']],
    ['label' => Yii::t('proshop', 'Scan'), 'url' => ['/translatemanager/language/scan']],
    ['label' => Yii::t('proshop', 'Optimize'), 'url' => ['/translatemanager/language/optimizer']],
    ['label' => Yii::t('proshop', 'Import'), 'url' => ['/translatemanager/language/import']],
    ['label' => Yii::t('proshop', 'Export'), 'url' => ['/translatemanager/language/export']],

];
?>


<div class="row">
    <div class="col-md-3">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title"><?= Yii::t('proshop', 'Actions') ?></span>
            </div>
            <div class="panel-body">

                <div class="list-group">
                    <?= \yii\helpers\Html::a(Yii::t('language', 'List of languages'), ['/translatemanager/language/list'], ['class' => 'list-group-item']) ?>
                    <?= \yii\helpers\Html::a(Yii::t('language', 'Create'), ['/translatemanager/language/create'], ['class' => 'list-group-item']) ?>
                </div>
                <hr>
                <div class="list-group">
                    <?= \yii\helpers\Html::a(Yii::t('language', 'Scan'), ['/translatemanager/language/scan'], [
                        'class' => 'list-group-item',
                        'data-confirm' => Yii::t('language', 'Are you sure you want to scan for new language elements? This will take a while.')
                    ]) ?>
                    <?= \yii\helpers\Html::a(Yii::t('language', 'Optimize'), ['/translatemanager/language/optimizer'], ['class' => 'list-group-item']) ?>
                </div>
                <hr>
                <div class="list-group">
                    <?= \yii\helpers\Html::a(Yii::t('language', 'Import'), ['/translatemanager/language/import'], ['class' => 'list-group-item']) ?>
                    <?= \yii\helpers\Html::a(Yii::t('language', 'Export'), ['/translatemanager/language/export'], ['class' => 'list-group-item']) ?>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-9">
        <?= $content ?>
    </div>
</div>


