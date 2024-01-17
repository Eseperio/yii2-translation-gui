<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model eseperio\translatemanager\models\LanguageTranslateEngine */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('language', 'Engine translation configuration');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <div class="box-body">
        <div class="language-form col-sm-6">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'engine')->dropDownList($model::getEngines())->label('Motor') ?>

            <?php
                $selectedEngine = $model->engine;
                echo "motor ". $selectedEngine;
                print_r($model::getDataEngine($selectedEngine));
            //        $model->apiKey = $model::getApiKey();
            ?>

            <?= $form->field($model, 'url')->textInput(['maxlength' => true])->label('URL') ?>

            <?= $form->field($model, 'apiKey')->textInput(['maxlength' => true])->label('Clave') ?>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
