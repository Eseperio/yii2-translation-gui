<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\translatemanager\models\LanguageSource;
use Yii;

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
     * @return \yii\web\Response
     */
    public function run()
    {

        $response = [
            'success' => true,
            'errors' => []
        ];
        $ids = Yii::$app->request->post('ids');

        $delted = LanguageSource::deleteAll(['id' => (array)$ids]);
        if ($delted === false) {
            $response['success'] = false;
            $response['errors'][] = Yii::t('language', 'An error occured while deleting the sources');
        }
        return $this->controller->asJson($response);


    }
}
