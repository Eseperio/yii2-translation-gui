<?php

namespace eseperio\translatemanager\engines;

use eseperio\proshop\common\components\OpenAIComponent;
use eseperio\translatemanager\interfaces\TranslationEngine;
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

        if (!empty($response['error'])) {
            Yii::$app->session->addFlash('danger', $response['error']);
        }

        return $response['message'] ?? null;
    }
}