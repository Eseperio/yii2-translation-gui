<?php


/* @var $this \yii\web\View */
/* @var $languageAvailable array */

/* @var $languagesEnabled array */

use yii\helpers\Html;

$this->title = Yii::t('translatemanager', 'Translation manager')
?>

<h1><?= Yii::t('translatemanager', 'Translation manager tool') ?></h1>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                This is the list of languages enabled in the application through the translation configuration files
                <?php
                if (empty($languagesEnabled)) {
                    echo '<div class="alert alert-warning">No languages enabled</div>';
                }
                foreach ($languagesEnabled as $language) {
                    ?>
                    <li class="list-group-item">
                        <?= $language['ascii_name']; ?>
                        <?= Html::a(Yii::t('translatemanager', 'Edit translations'), ['language/translate', 'locale' => $language['language_id']]) ?>
                        <?= Html::a('<i class="fa fa-trash"></i>', ['language/remove'], [
                            'class' => 'text-danger pull-right',
                            'data' => [
                                'params' => ['locale' => $language['language_id']],
                                'method' => 'post',
                                'confirm' => Yii::t('translatemanager', 'Are you sure you want to remove this language?')
                            ],

                        ]) ?>
                    </li>


                    <?php
                }
                ?>
                <hr>
                <?php echo Html::beginForm(['/translatemanager/language/add']); ?>
                <div class="form-group">
                    <?= Html::dropDownList('locale', null, $languageAvailable, ['class' => 'form-control']); ?>

                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('translatemanager', 'Enable a language'), ['class' => 'btn btn-primary']); ?>
                </div>

            </div>
            <div class="panel-heading">Languages enabled</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Connection to third party tools</div>
            <div class="panel-body">
                Here is a list of third party tools that can be connected to this application
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    ChatGPT <?= Html::a(Yii::t('translatemanager', 'Configure'), ['chatgpt/configure']) ?>
                </li>
                <li class="list-group-item">
                    DeepL <?= Html::a(Yii::t('translatemanager', 'Configure'), ['chatgpt/configure']) ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Panel 3</div>
            <div class="panel-body">Panel content</div>
        </div>
    </div>
</div>

