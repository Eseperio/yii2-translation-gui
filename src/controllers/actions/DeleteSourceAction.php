<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\translatemanager\models\LanguageSource;
use Yii;
use yii\web\Response;

/**
 * Deletes an existing LanguageSource model.
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.4
 */
class DeleteSourceAction extends \yii\base\Action
{
    /**
     * Deletes an existing LanguageSource model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     *
     * @return array
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $ids = Yii::$app->request->post('ids');

        LanguageSource::deleteAll(['id' => (array) $ids]);

        return [];
    }
}
