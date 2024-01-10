<?php

namespace eseperio\translatemanager\services;

use eseperio\translatemanager\extractors\DbMessageExtractor;
use eseperio\translatemanager\extractors\GettextMessageExtractor;
use eseperio\translatemanager\extractors\PhpMessageExtractor;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\i18n\MessageSource;

/**
 * Helps to load messages from different message sources
 */
class MessageLoaderService extends BaseObject
{

    /**
     * @param $category
     * @param \yii\i18n\MessageSource $messageSource
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     */
    public static function getMessages($category, MessageSource $messageSource)
    {

        try {
            if ($messageSource instanceof \yii\i18n\PhpMessageSource) {
                $extractor = self::createExtractor($messageSource, PhpMessageExtractor::class);
                return $extractor->getCategoryMessages($category, \Yii::$app->language);
            } elseif ($messageSource instanceof \yii\i18n\DbMessageSource) {
                $extractor = self::createExtractor($messageSource, DbMessageExtractor::class);
                return $extractor->getCategoryMessages($category, \Yii::$app->language);
            } elseif ($messageSource instanceof \yii\i18n\GettextMessageSource) {
                $extractor = self::createExtractor($messageSource, GettextMessageExtractor::class);
                return $extractor->getCategoryMessages($category, \Yii::$app->language);
            }
        } catch (InvalidConfigException $e) {
            \Yii::error($e->getMessage());
            if(YII_DEBUG){
                throw $e;
            }
        }

        throw new NotSupportedException('Message source not supported');
    }


    /**
     * @param \yii\i18n\PhpMessageSource $messageSource
     * @param $extractorClass
     * @return PhpMessageExtractor|DbMessageExtractor|GettextMessageExtractor
     * @throws \yii\base\InvalidConfigException
     */
    private static function createExtractor(MessageSource $messageSource, $extractorClass)
    {
        $vars = \Yii::getObjectVars($messageSource);
        $vars['class'] = $extractorClass;

        return \Yii::createObject($vars);
    }


}
