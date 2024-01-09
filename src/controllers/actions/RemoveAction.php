<?php

namespace eseperio\translatemanager\controllers\actions;

use yii\base\Action;

/**
 * Process the request to remove a  language received from the form on the iso param.
 * @property \eseperio\translatemanager\controllers\LanguageController $controller
 */
class RemoveAction extends Action
{
    public function run()
    {
        $language = \Yii::$app->request->post('locale');
        if (empty($language)) {
            throw new \yii\web\BadRequestHttpException("No language provided");
        }

        $this->controller->module->disableLanguage($language);
        return $this->controller->redirect('index');

    }

}
