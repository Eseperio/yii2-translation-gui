<?php

namespace eseperio\translatemanager\controllers\actions;

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
        $categories = $this->getCategories();
        // add progress to categories
        foreach ($categories as $key => $category) {
            $categories[$key] = [
                'category' => $category,
                'progress' => $this->controller->module->languageProgress($locale, $category)
            ];
        }
        $language = $this->controller->module->getLanguageData($locale);

        return $this->controller->render('translate', [
            'categories' => $categories,
            'language' => $language
        ]);

    }

    /**
     * @return array with all the categories for translations defined in the application
     */
    private function getCategories()
    {
        $i18n = Yii::$app->i18n;
        $categories = array_keys($i18n->translations);

        return $categories;
    }
}
