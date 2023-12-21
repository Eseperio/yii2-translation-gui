<?php

namespace eseperio\translatemanager\engines;

use eseperio\translatemanager\interfaces\TranslationEngine;
use GuzzleHttp\Client as HttpClient;

// TODO: check if it's work
class GoogleTranslate implements TranslationEngine
{
    public static function getTranslation($text, $source = 'en', $target = 'es')
    {
        $apiKey = $_ENV['GOOGLE_TRANSLATE_API_KEY'];

        $httpClient = new HttpClient();
        $response = $httpClient->request(
            'POST',
            'https://translation.googleapis.com/language/translate/v2', [
            'query' => [
                'key' => $apiKey,
            ],
            'json' => [
                'q' => $text,
                'source' => $source,
                'target' => $target,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        return $responseData['data']['translations'][0]['translatedText'] ?? $text;
    }

    public function getBulkTranslation($params = [])
    {
        // Implementación para traducción masiva si es necesario
    }
}