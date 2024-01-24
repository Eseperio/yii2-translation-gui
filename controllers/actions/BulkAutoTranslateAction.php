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

        $stringsToTranslate = LanguageSource::find()
            ->select(['language_source.id', 'message'])
            ->leftJoin('language_translate', 'language_source.id = language_translate.id AND language_translate.language = :language', [':language' => $languageId])
            ->where(['language_translate.translation' => null])
            ->asArray()
            ->all();

        if ($action === 'translateLanguage') { // Return translated strings
            $response = OpenAi::getBulkTranslation($stringsToTranslate, $languageSource , $languageId);
            
            return $response;
        } else if ($action == 'getModalContent') { // Return total chars to translate
            $stringsToTranslate = array_column($stringsToTranslate, 'message');

            return [
                'status' => 'success',
                'totalCharts' => strlen(implode($stringsToTranslate)),
                'action' => $action,
                'totalTranslations' => count($stringsToTranslate),
            ];
        }
    }
}
