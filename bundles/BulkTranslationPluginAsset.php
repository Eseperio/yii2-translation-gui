<?php

namespace eseperio\translatemanager\bundles;

use yii\web\AssetBundle;

class BulkTranslationPluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@eseperio/translatemanager/assets';

    /**
     * @inheritdoc
     */
    public $js = [
//        'javascripts/helpers.js',
        'javascripts/bulk-translation.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'eseperio\translatemanager\bundles\TranslationPluginAsset',
    ];
}