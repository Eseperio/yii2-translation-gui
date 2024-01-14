<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\proshop\common\helpers\ArrayHelper;
use yii\base\Action;

/**
 * @property  \eseperio\translatemanager\controllers\LanguageController $controller
 */
class IndexAction extends Action
{

    /**
     * @throws \yii\base\Exception
     */
    public function run()
    {
        $availableLanguages = $this->controller->module->getAvailableLanguages();
        $languagesEnabled = $this->controller->module->getEnabledLanguages();

        $languagesEnabled = array_intersect_key($availableLanguages, array_flip($languagesEnabled));

        $availableLanguages = ArrayHelper::map($availableLanguages, 'language_id', function ($model) {
            return $model['name'] . ' (' . $model['ascii_name'] . ')';
        });
        return $this->controller->render('index', [
            'languagesEnabled'=> $languagesEnabled,
            'languageAvailable'=> $availableLanguages,
        ]);
    }


}
