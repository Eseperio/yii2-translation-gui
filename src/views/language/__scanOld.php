<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.4
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this \yii\web\View */
/* @var $oldDataProvider \yii\data\ArrayDataProvider */

$deleteSelectedUrl = Url::to('/translatemanager/language/delete-source');

$this->registerJs(
    new JsExpression(<<<JS
        // Manejar clic en el botón de eliminar seleccionados
        $('#delete-selected').click(function() {
            var selectedIds = $('[id^=delete-source]').yiiGridView('getSelectedRows');
            if(selectedIds.length > 0){
                deleteLanguageRows(selectedIds);
            }
        });
    $('.delete-item').click(function() {
     var id = $(this).data('id');
     deleteLanguageRows([id]);
    });
    function deleteLanguageRows(ids){
        $.ajax({
            url: '$deleteSelectedUrl',
            type: 'post',
            data: {ids: ids},
            success: function(data) {
                if(data.success){
                    ids.forEach(function(id){
                        $('tr[data-key=' + id + ']').remove();
                    });
                }else{
                    alert(data.error);
                }
            }
        });
    }
JS
    )
);
?>
<?php if ($oldDataProvider->totalCount > 1) : ?>
    <?= Html::button(Yii::t('language', 'Delete selected'), ['id' => 'delete-selected', 'class' => 'btn btn-danger']) ?>
<?php endif ?>

<?php if ($oldDataProvider->totalCount > 0) : ?>

    <?=

    GridView::widget([
        'id' => 'delete-source',
        'dataProvider' => $oldDataProvider,
        'rowOptions' => function ($model) {
            return ['key' => $model['id']];
        },
        'columns' => [
            [
                'class' => \yii\grid\CheckboxColumn::class,
                'checkboxOptions' => function ($languageSource) {
                    return ['value' => $languageSource['id'], 'class' => 'language-source-cb'];
                }
            ],
            'id',
            'category',
            'message',
            'languages',
            [
                'format' => 'raw',
                'attribute' => Yii::t('language', 'Action'),
                'content' => function ($languageSource) {
                    return Html::a(Yii::t('language', 'Delete'), Url::toRoute('/translatemanager/language/delete-source'), ['data-id' => $languageSource['id'], 'class' => 'delete-item btn btn-xs btn-danger']);
                },
            ],
        ],
    ]);

    ?>

<?php endif ?>
