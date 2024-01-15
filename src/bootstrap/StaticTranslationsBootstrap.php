<?php

namespace eseperio\translatemanager\bootstrap;

use Yii;
use yii\base\BootstrapInterface;

class StaticTranslationsBootstrap implements BootstrapInterface
{

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['language'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@eseperio/translatemanager/messages',
        ];
        $i18n->translations['model'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@eseperio/translatemanager/messages',
        ];
    }
}
