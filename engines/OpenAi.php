<?php

namespace eseperio\translatemanager\engines;

use eseperio\proshop\common\components\OpenAIComponent;
use eseperio\translatemanager\interfaces\TranslationEngine;
use Yii;

/**
 * Implements support for openAi translation engine
 */
class OpenAi implements TranslationEngine
{
    /**
     * @param array $texts
     * @param string $source
     * @param string $target
     * @return array|null
     */
    public static function getTranslation($texts, $source = 'EN', $target = 'ES')
    {
        $translator = new OpenAIComponent;
        $category = 'info';

        $response = $translator->translate($texts, $source, $target);

        if (!empty($response['error'])) {
            Yii::$app->session->addFlash('danger', $response['error']);
        }

        return $response['message'] ?? null;
    }
}