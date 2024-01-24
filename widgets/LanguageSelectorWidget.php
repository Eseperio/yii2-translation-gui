<?php

namespace eseperio\translatemanager\widgets;

use eseperio\translatemanager\models\Language;
use eseperio\translatemanager\widgets\assets\LanguageSelectorAsset;
use OpenAI;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class LanguageSelectorWidget extends Widget
{
    public $languages = [];

    public function init()
    {
        parent::init();
        $this->languages = Language::getActiveLanguages();
        $this->languages = ArrayHelper::map($this->languages, 'name', 'name');

    }

    public function run()
    {
        phpinfo();
        exit;
        // Registra los assets del widget
//        LanguageSelectorAsset::register($this->view);

        $languages = Language::getActiveLanguages();

//        $prb = \eseperio\translatemanager\engines\OpenAi::getTranslation(
//            [
//               3 => "I enjoy learning new things.",
//               4 => "The quick brown fox jumps over the lazy dog.",
//            ]
//            , 'EN', 'ES');
//
//        print_r($prb);

        // Genera el botón desplegable
        return Html::dropDownList('language-selector', null, $this->languages, ['class' => 'language-selector']);
    }
}
