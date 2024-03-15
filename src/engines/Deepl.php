<?php

namespace eseperio\translatemanager\engines;

use eseperio\translatemanager\interfaces\TranslationEngine;
use eseperio\translatemanager\models\LanguageTranslate;
use eseperio\translatemanager\services\Generator;
use yii\httpclient\Client;
use Yii;

class Deepl implements TranslationEngine
{
    public static function getTranslation($text, $source = 'EN', $target = 'ES')
    {
        $data = [
            'auth_key' => $_ENV['DEEPL_API_KEY'],
            'text' => $text,
            'source_lang' => $source, // Cambia según tu idioma fuente
            'target_lang' => $target, // Cambia según tu idioma de destino
        ];

        $httpclient = new Client();
        $response = $httpclient->createRequest()
            ->setMethod('POST')
            ->setUrl($_ENV['DEEPL_URL'])
            ->setData($data)
            ->send();

        $responseData = json_decode($response->content, true);

        return $responseData['translations'][0]['text'] ?? $text;
    }
    
    public static function getBulkTranslation($stringsToTranslate, $source = 'en-US', $target = 'es-ES')
    {
        foreach ($stringsToTranslate as $string) {

            $languageTranslate = LanguageTranslate::findOne(['id' => $string['id'], 'language' => $target]) ?:
                new LanguageTranslate(['id' => $string['id'], 'language' => $target]);

            $translateText = Deepl::getTranslation(
                $string['message'],
                Deepl::getLangISO($source),
                Deepl::getLangISO($target)
            );

            $languageTranslate->translation = Deepl::getTranslatedText($string['message'], $translateText);

            if ($languageTranslate->validate() && $languageTranslate->save()) {
                $generator = new Generator(Yii::$app->getModule('translatemanager'), $target);
                $generator->run();
            }

            if ($languageTranslate->getErrors()) {
                return [
                    'status' => 'error',
                    'errors' => $languageTranslate->getErrors(),
                ];
            }/* else { // TODO: borrar else para funcionamiento normal del botón y que no se detenga en la primera traducción
                return [
                    'status' => 'success',
                    'translation' => $languageTranslate->translation,
                ];
            }*/
        }
        return [
            'status' => 'success',
        ];
    }

    /**
     * @param $textSource
     * @param $text
     * @return array|string|string[]
     */
    private static function getTranslatedText($textSource, $text) {
        // Encuentra todas las coincidencias de texto entre corchetes en el texto en inglés
        preg_match_all('/\{([^}]+)\}/', $textSource, $matchesSource);
        preg_match_all('/\{([^}]+)\}/', $text, $matches);

        return str_replace($matches[1], $matchesSource[1], $text);
    }

    /**
     * @param $lang
     * @return string
     */
    private static function getLangISO($lang): string
    {
        $parts = explode('-', $lang, 2);

        if (count($parts) < 2) {
            return $lang;
        }

        return strtoupper($parts[0]);
    }

}
