<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\translatemanager\engines\Deepl;
use eseperio\translatemanager\engines\OpenAi;
use Yii;
use yii\base\Action;
use yii\httpclient\Client;
use yii\web\Response;
use eseperio\translatemanager\services\Generator;
use eseperio\translatemanager\models\LanguageTranslate;

class AutoTranslateAction extends Action
{
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id', 0);
        $languageSource = Yii::$app->sourceLanguage;
        $languageId = Yii::$app->request->post('language_id', Yii::$app->language);
        $languageTranslate = LanguageTranslate::findOne(['id' => $id, 'language' => $languageId]) ?:
            new LanguageTranslate(['id' => $id, 'language' => $languageId]);
        $languageTranslate->translation = '';
        if (Yii::$app->request->post('auto_translate', '') === 'true'){
            $languageTranslate->translation = Deepl::getTranslation(
                Yii::$app->request->post('source', ''),
                $this->getLangISO($languageSource), $this->getLangISO($languageId)
            );
        }
//        return $languageTranslate->translation;

        if ($languageTranslate->validate() && $languageTranslate->save()) {
            $generator = new Generator($this->controller->module, $languageId);
            $generator->run();
        }
        if ($languageTranslate->getErrors()) {
            return [
                'status' => 'error',
                'errors' => $languageTranslate->getErrors()
            ];
        } else {
            return [
                'status' => 'success',
                'translation' => $languageTranslate->translation,
            ];
        }
    }

    private function getLangISO($lang)
    {
        $parts = explode('-', $lang, 2);

        if (count($parts) < 2) {
            return $lang;
        }

        return strtoupper($parts[0]);
    }
}
