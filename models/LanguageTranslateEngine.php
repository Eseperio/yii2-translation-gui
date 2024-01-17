<?php

namespace eseperio\translatemanager\models;

use eseperio\translatemanager\interfaces\TranslationEngine;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 *
 */
class LanguageTranslateEngine extends ActiveRecord
{
    /**
     * @var
     */
    public $engine;
    /**
     * @var
     */
    public $url;
    /**
     * @var
     */
    public $apiKey;
    /**
     * @var
     */
    public $active;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%language_translate_engine}}';
//        return '{{%configuration}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['engine', 'url', 'apiKey'], 'required'],
            [['engine', 'url', 'apiKey'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public static function getEngines() {
        /* get from db all engines with all data */
        $sql = LanguageTranslateEngine::find()->select('engine_id, engine')
            ->orderBy(['active' => SORT_DESC])->asArray()->all();

        return ArrayHelper::map($sql, 'engine_id', 'engine');
    }

    /**
     * @param $id_engine
     * @return array
     */
    public static function getDataEngine($engine_id) {
        echo $engine_id;
        return LanguageTranslateEngine::find()
            ->where(['engine_id' => $engine_id])->column();

    }

    /**
     * @param TranslationEngine $engine
     * @param $text
     * @param $source
     * @param $target
     * @return mixed
     */
    public function translate(TranslationEngine $engine, $text, $source, $target)
    {
        return $engine::getTranslation($text, $source, $target);
    }

    /**
     * @param TranslationEngine $engine
     * @param $params
     * @return mixed
     */
    public function bulkTranslate(TranslationEngine $engine, $params)
    {
        return $engine->getBulkTranslation($params);
    }
}