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
        Yii::$app->i18n->translations['translatemanager'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@eseperio/translatemanager/messages',
        ];
    }
}
