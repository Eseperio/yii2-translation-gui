<?php

namespace eseperio\translatemanager\interfaces;

interface TranslationEngine
{
    /**
     * Api call to Translate to a string.
     * @param string $text Text to translate
     * @param string $source Source language
     * @param string $target Target language
     */
    public static function getTranslation($text, $source, $target);
    
}