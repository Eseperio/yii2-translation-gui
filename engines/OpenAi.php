<?php

namespace eseperio\translatemanager\engines;

use eseperio\proshop\common\components\OpenAIComponent;
use eseperio\translatemanager\interfaces\TranslationEngine;
use eseperio\translatemanager\models\LanguageTranslate;
use eseperio\translatemanager\services\Generator;
use Yii;

class OpenAi implements TranslationEngine
{
    /**
     * @param array $text
     * [
     *      ID => "TEXT TO TRANSLATE",
     *      3 => "I enjoy learning new things.",
     *      4 => "The quick brown fox jumps over the lazy dog.",
     * ]
     * @param string $source
     * EN
     * @param string $target
     * ES
     * @return array|null
     */
    public static function getTranslation($text, $source = 'EN', $target = 'ES')
    {
        $translator = new OpenAIComponent;
        $category = 'info';

        $response = str_replace(["```json", "```"], ['', ''], $translator->translate($text, $source, $target));
//        $response = $translator->translate($text, $source, $target);

        return [
            'beforeText' => $text,
            'response' => $response,
        ];
        $isJson = json_decode($response, true);
        if ((is_object($isJson) || is_array($isJson))) {
//            if  (!empty($isJson['message'])) {
//                $response = $isJson['message'];
//            } else {
                $response = $isJson;
//            }
        }

        if (!empty($response['error'])) {
            Yii::$app->session->addFlash('danger', $response['error']);
        }

        return $response;
    }
    
    public static function getBulkTranslation($stringsToTranslate, $source = 'en-US', $target = 'es-ES')
    {
        $loop = 5;
        $loopElements = array_slice($stringsToTranslate, 0, $loop); // Get first 500 elements from array
        $countChartsToIa = strlen(json_encode($loopElements)); // Get the length of the json string

        $max_chars = 30000; // Max chars to send to IA

        if ($countChartsToIa < $max_chars) {
//            return [
//                'Loop' => $loop,
//                'LoopElements' => $loopElements,
//                'CountChartsToIa' => $countChartsToIa,
//            ];
            $response = OpenAi::getTranslation($loopElements, $source, $target);

//            foreach ($response as $value) {

//                $languageTranslate = LanguageTranslate::findOne(['id' => $value['id'], 'language' => $target]) ?:
//                    new LanguageTranslate(['id' => $value['id'], 'language' => $target]);
//
//                $languageTranslate->translation = $value['message'];
//
//                if ($languageTranslate->validate() && $languageTranslate->save()) {
//                    $generator = new Generator(Yii::$app->getModule('translatemanager'), $target);
//                    $generator->run();
//                }
//
//                if ($languageTranslate->getErrors()) {
//                    return [
//                        'status' => 'error',
//                        'errors' => $languageTranslate->getErrors(),
//                    ];
//                }
//            }

            return [
                'status' => 'success',
                'translated_string' => $loop,
                'response' => $response,
//                'response2' => json_decode($response, true),
            ];
        } else {
            return [
                'status' => 'error',
                'errors' => 'El total de caracteres enviados a la IA supera el límite de 32000 caracteres.'
            ];
        }
    }
}