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
     * @param array $texts
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
    public static function getTranslation($texts, $source = 'EN', $target = 'ES')
    {
        $translator = new OpenAIComponent;
        $category = 'info';

        $response = $translator->translate($texts, $source, $target);

//        $isJson = json_decode($response, true);
//        if ((is_object($isJson) || is_array($isJson))) {
//            $response = $isJson['message'];
//        }

        if (!empty($response['error'])) {
            Yii::$app->session->addFlash('danger', $response['error']);
        }

        return $response;
    }
    
    public static function getBulkTranslation($stringsToTranslate, $source = 'en-US', $target = 'es-ES')
    {

//        $cad1 = "```json\n{\"message\": [\n    {\"id\": 2638,\"message\": \"{delta, plural, =1{1 minuto} other{# minutos}\"},\n    {\"id\": 2639,\"message\": \"{delta, plural, =1{1 segundo} other{# segundos}\"},\n    {\"id\": 2640,\"message\": \"{nFormatted} B\"},\n    {\"id\": 2641,\"message\": \"{nFormatted} KiB\"},\n    {\"id\": 2642,\"message\": \"{nFormatted} MiB\"}\n],\n \"error\": null\n}\n```";
//        $cad2 = "{\n  \"message\": [\n    {\n      \"id\": \"{delta, plural, =1{1 minute} other{# minutes}\",\n      \"message\": \"{delta, plural, =1{1 minuto} other{# minutos}\"\n    },\n    {\n      \"id\": \"{delta, plural, =1{1 second} other{# seconds}\",\n      \"message\": \"{delta, plural, =1{1 segundo} other{# segundos}\"\n    },\n    {\n      \"id\": \"{nFormatted} B\",\n      \"message\": \"{nFormatted} B\"\n    },\n    {\n      \"id\": \"{nFormatted} KiB\",\n      \"message\": \"{nFormatted} KiB\"\n    },\n    {\n      \"id\": \"{nFormatted} MiB\",\n      \"message\": \"{nFormatted} MiB\"\n    }\n  ],\n  \"error\": null\n}";
//
//        json_decode($cad1);
//        json_decode($cad2);
//
//        return [
//            'cad1' => json_decode($cad1),
//            'cad2' => json_decode($cad2),
//        ];

        $loop = 5;
        $loopElements = array_slice($stringsToTranslate, 0, $loop); // Get first 500 elements from array
        $countChartsToIa = strlen(json_encode($loopElements)); // Get the length of the json string

        $max_chars = 30000; // Max chars to send to IA

        if ($countChartsToIa < $max_chars) {
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
//            return [
//                'primeros_500_traducidos' => $response,
//                'count' => $countChartsToIa
//            ];

            return [
                'status' => 'success',
                'translated_string' => $loop,
                'response' => $response,
            ];
        } else {
            return [
                'status' => 'error',
                'errors' => 'El total de caracteres enviados a la IA supera el límite de 32000 caracteres.'
            ];
        }
    }
}