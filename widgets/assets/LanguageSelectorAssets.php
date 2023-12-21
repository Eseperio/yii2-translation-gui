<?php

namespace eseperio\translatemanager\widgets\assets;

use yii\web\AssetBundle;

class LanguageSelectorAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/assets';

    public $css = [
        'css/language-selector.css', // Estilo del botón desplegable
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}