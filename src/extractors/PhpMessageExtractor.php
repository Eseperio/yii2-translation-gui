<?php

namespace eseperio\translatemanager\extractors;

use eseperio\translatemanager\traits\ExtractorTrait;
use Yii;
use yii\helpers\FileHelper;
use yii\i18n\PhpMessageSource;

/**
 * Methods for extracting messages from php files in PhpMessageSource are protected
 * so we need to extend it to access them.
 */
class PhpMessageExtractor extends PhpMessageSource
{
    use ExtractorTrait;

    /**
     * Categories are defined inline on each translation call, and in configuration there are only patterns.
     * If category contains pattern, we search categories on fileMap and merge all the results.
     * @param $category
     * @param $language
     * @return array
     */
    protected function loadMessages($category, $language)
    {
        // if category contains a wildcard, find all the php files that match the pattern within the path on basePath
        if (str_ends_with($category, '*')) {
            return $this->loadMessagesFromPattern($category, $language);
        }

        return [
            $category => parent::loadMessages($category, $language)
        ];
    }

    /**
     * @param string $category
     * @param string $language
     * @return array
     */
    private function loadMessagesFromPattern(string $category, string $language): array
    {
        $messages = [];
        $files = [];
        $langDir = Yii::getAlias($this->basePath) . "/$language/";
        if(!is_dir($langDir)){
            return [];
        }

        if (is_array($this->fileMap)) {
            foreach ($this->fileMap as $cat => $file) {
                if (fnmatch($category, $cat)) {
                    $files[$cat] =  $langDir.$file;
                }
            }
        } else {
            $files = FileHelper::findFiles($langDir, [
                'only' => ['*.php'],
                'filter' => function ($path) use ($category) {
                    return fnmatch($category, basename($path, '.php'));
                }
            ]);
            $messages = [];
            foreach ($files as $cat => $file) {
                if(is_numeric($cat)){
                    $cat = basename($file, '.php');
                }
                $messages[$cat] = include $file;
            }
        }
        return $messages;
    }
}
