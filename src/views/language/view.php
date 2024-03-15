<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.3
 */

use eseperio\translatemanager\widgets\LanguageSelectorWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \eseperio\translatemanager\models\Language */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-view col-sm-6">
    <p>
        <?= Html::a(Yii::t('language', 'Update'), ['update', 'id' => $model->language_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('language', 'Delete'), ['delete', 'id' => $model->language_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('language', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        LanguageSelectorWidget::widget([
            'languages' => ['en' => 'English', 'es' => 'Español'],
        ]);
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'language_id',
            'language',
            'country',
            'name',
            'name_ascii',
            [
                'label' => Yii::t('language', 'Status'),
                'value' => $model->getStatusName(),
            ],
            [
                'label' => Yii::t('language', 'Translation status'),
                'value' => $model->getGridStatistic() . '%',
            ],
        ],
    ])
    ?>

</div>
