<?php

namespace eseperio\translatemanager\engines;

use eseperio\translatemanager\interfaces\TranslationEngine;
use Aws\Translate\TranslateClient;

// TODO: check if it's work
class AmazonTranslate implements TranslationEngine
{
    public static function getTranslation($text, $source = 'en', $target = 'es')
    {
        $apiKey = $_ENV['AWS_ACCESS_KEY_ID'];
        $secretKey = $_ENV['AWS_SECRET_ACCESS_KEY'];
        $region = $_ENV['AWS_DEFAULT_REGION'];

        $translateClient = new TranslateClient([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $apiKey,
                'secret' => $secretKey,
            ],
        ]);

        $result = $translateClient->translateText([
            'Text' => $text,
            'SourceLanguageCode' => $source,
            'TargetLanguageCode' => $target,
        ]);

        return $result['TranslatedText'] ?? $text;
    }
}