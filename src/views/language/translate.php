<?php

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */


/* @var $this \yii\web\View */
/* @var $language array */
/* @var $categories string[] List of categories available */


$this->title = Yii::t('translatemanager', 'Translation into {language_id}', ['language_id' => $language['language_id']]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('translatemanager', 'Translation tool'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsVar('LOCALE_IN_TRANSLATION', $language['language_id']);
?>

<div id="translates" class="<?= $language['language_id'] ?>">
    <ul class="list-group">
        <?php foreach ($categories as $category) {
            ?>
            <li class="list-group-item">
                <?= $category['category'] ?>
                <?= Yii::$app->formatter->asPercent($category['progress']) ?>

            </li>
            <?php
        }
        ?>
    </ul>
</div>
