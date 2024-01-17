<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\translatemanager\bundles\BulkTranslationPluginAsset;
use eseperio\translatemanager\engines\Deepl;
use eseperio\translatemanager\engines\OpenAi;
use eseperio\translatemanager\models\LanguageSource;
use Yii;
use yii\base\Action;
use yii\web\Response;
use eseperio\translatemanager\services\Generator;
use eseperio\translatemanager\models\LanguageTranslate;

/**
 * Handles bulk translation
 */
class BulkAutoTranslateAction extends Action
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        BulkTranslationPluginAsset::register(Yii::$app->controller->view);
        parent::init();
    }

    /**
     * @return void
     */
    public static function registerAssets()
    {
        BulkTranslationPluginAsset::register(Yii::$app->view);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $languageSource = 'en-US'; //TODO: get from config
        $languageId = Yii::$app->request->post('language_id', Yii::$app->language);
        $action = Yii::$app->request->post('action', Yii::$app->language);

        $totalCharts = 0;

        $stringsToTranslate = LanguageSource::find()
            ->select(['language_source.id', 'message'])
            ->leftJoin('language_translate', 'language_source.id = language_translate.id AND language_translate.language = :language', [':language' => $languageId])
            ->where(['language_translate.translation' => null])
            ->asArray()
            ->all();

        // Calcular la longitud total de las cadenas
        foreach ($stringsToTranslate as $string) {
            $totalCharts += strlen($string['message']);

            if ($action === 'translateLanguage') {
                $languageTranslate = LanguageTranslate::findOne(['id' => $string['id'], 'language' => $languageId]) ?:
                new LanguageTranslate(['id' => $string['id'], 'language' => $languageId]);

                $translateText = Deepl::getTranslation(
                    $string['message'],
                    $this->getLangISO($languageSource),
                    $this->getLangISO($languageId)
                );
//                $translateText = OpenAi::getTranslation(
//                    $string['message'],
//                    $this->getLangISO($languageSource),
//                    $this->getLangISO($languageId)
//                );

                $languageTranslate->translation = $this->getTranslatedText($string['message'], $translateText);

                if ($languageTranslate->validate() && $languageTranslate->save()) {
                    $generator = new Generator($this->controller->module, $languageId);
                    $generator->run();
                }

                if ($languageTranslate->getErrors()) {
                    return [
                        'status' => 'error',
                        'errors' => $languageTranslate->getErrors(),
                    ];
                } else { // borrar else para funcionamiento normal del botón y que no se detenga en la primera traducción
                    return [
                        'status' => 'success',
                        'translation' => $languageTranslate->translation,
                    ];
                }
            }

        }

        return [
            'status' => 'success',
//            'translations' => $stringsToTranslate,
            'totalCharts' => $totalCharts,
            'action' => $action
        ];
    }

    /**
     * @param $lang
     * @return string
     */
    private function getLangISO($lang): string
    {
        $parts = explode('-', $lang, 2);

        if (count($parts) < 2) {
            return $lang;
        }

        return strtoupper($parts[0]);
    }

    /**
     * @param $textSource
     * @param $text
     * @return array|string|string[]
     */
    private function getTranslatedText($textSource, $text) {
        // Encuentra todas las coincidencias de texto entre corchetes en el texto en inglés
        preg_match_all('/\{([^}]+)\}/', $textSource, $matchesSource);
        preg_match_all('/\{([^}]+)\}/', $text, $matches);

        return str_replace($matches[1], $matchesSource[1], $text);
    }
}
