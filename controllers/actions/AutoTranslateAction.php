<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\translatemanager\engines\Deepl;
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
        $languageSource = 'en-US'; //TODO: get from config
        $languageId = Yii::$app->request->post('language_id', Yii::$app->language);
        $languageTranslate = LanguageTranslate::findOne(['id' => $id, 'language' => $languageId]) ?:
            new LanguageTranslate(['id' => $id, 'language' => $languageId]);
        $languageTranslate->translation = $this->translateWithDeepL(
            Yii::$app->request->post('source', ''),
            $this->getLangISO($languageSource), $this->getLangISO($languageId)
        );
//        $languageTranslate->translation = Deepl::getTranslation(
//            Yii::$app->request->post('source', ''),
//            $this->getLangISO($languageSource), $this->getLangISO($languageId)
//        );
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
                'translation' => $languageTranslate->translation
            ];
        }
//        return $languageTranslate->getErrors();
    }

//    private function translateWithDeepL($text, $source = 'EN', $target = 'ES')
//    {
//        $apiKey = $_ENV['DEEPL_API_KEY'];
//        $apiUrl = $_ENV['DEEPL_URL'];
//
//        $data = [
//            'auth_key' => $apiKey,
//            'text' => $text,
//            'source_lang' => $source, // Cambia según tu idioma fuente
//            'target_lang' => $target, // Cambia según tu idioma de destino
//        ];
//
//        $httpclient = new Client();
//        $response = $httpclient->createRequest()
//            ->setMethod('POST')
//            ->setUrl($apiUrl)
//            ->setData($data)
//            ->send();
//
//        $responseData = json_decode($response->content, true);
//
//        if (isset($responseData['translations'][0]['text'])) {
//            return $responseData['translations'][0]['text'];
//        } else {
//            return $text;
////            $id = Yii::$app->request->post('id', 0);
//        }
//    }

    private function getLangISO($lang)
    {
        $parts = explode('-', $lang, 2);

        if (count($parts) < 2) {
            return $lang;
        }

        return strtoupper($parts[0]);
    }
}