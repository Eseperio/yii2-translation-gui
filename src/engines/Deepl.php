<?php

namespace eseperio\translatemanager\engines;

use eseperio\translatemanager\interfaces\TranslationEngine;
use yii\httpclient\Client;

class Deepl implements TranslationEngine
{
    public static function getTranslation($text, $source = 'EN', $target = 'ES')
    {
        if (!isset($_ENV['DEEPL_API_KEY']) || !isset($_ENV['DEEPL_URL'])) {
            return $text;
        }

        $data = [
            'auth_key' => $_ENV['DEEPL_API_KEY'] ?? null,
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

    public function getBulkTranslation($params = [])
    {

    }
}
