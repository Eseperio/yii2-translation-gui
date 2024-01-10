<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\translatemanager\Module;
use Yii;

/**
 *
 * Renders a view with all the categories and its translation for given iso language code.
 * @property  \eseperio\translatemanager\controllers\LanguageController $controller
 */
class TranslateAction extends \yii\base\Action
{
    /**
     * List of language elements.
     *
     * @return string
     */
    public function run()
    {
        $locale = Yii::$app->request->get('locale', Yii::$app->language);
        $existingPatterns = $this->getPatterns();
        $patterns =[];
        // add progress to categories
        foreach ($existingPatterns as  $pattern) {
            $msgSrcCfg = Yii::$app->i18n->getMessageSource($pattern);
            $patterns[$pattern] = [
                'pattern' => $pattern,
                'messages' => $this->controller->module->loadMessages($pattern, $msgSrcCfg, $locale),
            ];
        }

        $language = $this->controller->module->getLanguageData($locale);

        return $this->controller->render('translate', [
            'patterns' => $patterns,
            'language' => $language
        ]);

    }

    /**
     * @return array with all the categories for translations defined in the application
     */
    private function getPatterns()
    {
        $i18n = Yii::$app->i18n;
        $categories = array_keys($i18n->translations);

        return $categories;
    }
}
