<?php

namespace eseperio\translatemanager\controllers\actions;

use yii\base\Action;

/**
 * Process the request to add a new language received from the form on the iso param.
 * @property \eseperio\translatemanager\controllers\LanguageController $controller
 */
class AddAction extends Action
{
    public function run()
    {
        $language = \Yii::$app->request->post('locale');
        if (empty($language)) {
            throw new \yii\web\BadRequestHttpException("No language provided");
        }

        $this->controller->module->enableLanguage($language);
        return $this->controller->redirect('index');

    }

}
