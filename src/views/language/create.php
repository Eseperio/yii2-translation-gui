<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.3
 */

/* @var $this yii\web\View */
/* @var $model \eseperio\translatemanager\models\Language */

$this->title = Yii::t('language', 'Create {modelClass}', [
    'modelClass' => 'Language',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@eseperio/translatemanager/views/layouts/main.php'); ?>

<div class="language-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php $this->endContent(); ?>
