<?php

namespace eseperio\translatemanager;

use eseperio\proshop\common\helpers\ArrayHelper;
use eseperio\proshop\common\helpers\StringHelper;
use eseperio\translatemanager\services\MessageLoaderService;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\i18n\MessageSource;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * This is the main module class for the TranslateManager module.
 *
 * Initialisation example:
 *
 * Simple example:
 *
 * ~~~
 * 'modules' => [
 *     'translatemanager' => [
 *         'class' => 'eseperio\translatemanager\Module',
 *     ],
 * ],
 * ~~~
 *
 * Complex example:
 *
 * ~~~
 * 'modules' => [
 *     'translatemanager' => [
 *         'class' => 'eseperio\translatemanager\Module',
 *         'root' => '@app',               // The root directory of the project scan.
 *         'layout' => 'language',         // Name of the used layout. If using own layout use 'null'.
 *         'allowedIPs' => ['127.0.0.1'],  // IP addresses from which the translation interface is accessible.
 *         'roles' => ['@'],               // For setting access levels to the translating interface.
 *         'tmpDir' => '@runtime',         // Writable directory for the client-side temporary language files.
 *                                         // IMPORTANT: must be identical for all applications (the AssetsManager serves the JavaScript files containing language elements from this directory).
 *         'phpTranslators' => ['::t'],    // list of the php function for translating messages.
 *         'jsTranslators' => ['lajax.t'], // list of the js function for translating messages.
 *         'patterns' => ['*.js', '*.php'],// list of file extensions that contain language elements.
 *         'ignoredCategories' => ['yii'], // these categories won’t be included in the language database.
 *         'ignoredItems' => ['config'],   // these files will not be processed.
 *         'languageTable' => 'language',  // Name of the database table storing the languages.
 *         'scanTimeLimit' => null,        // increase to prevent "Maximum execution time" errors, if null the default max_execution_time will be used
 *         'searchEmptyCommand' => '!',    // the search string to enter in the 'Translation' search field to find not yet translated items, set to null to disable this feature
 *         'defaultExportStatus' => 1,     // the default selection of languages to export, set to 0 to select all languages by default
 *         'defaultExportFormat' => 'json',// the default format for export, can be 'json' or 'xml'
 *         'tables' => [                   // Properties of individual tables
 *             [
 *                 'connection' => 'db',   // connection identifier
 *                 'table' => '{{%language}}',          // table name
 *                 'columns' => ['name', 'name_ascii'], //names of multilingual fields
 *                 'category' => 'database-table-name', // the category is the database table name
 *             ]
 *         ]
 *     ],
 * ],
 * ~~~
 *
 * IMPORTANT: If you want to modify the value of roles (in other words to start using user roles) you need to enable authManager in the common config.
 *
 * Using of authManager: http://www.yiiframework.com/doc-2.0/guide-security-authorization.html
 *
 * examples:
 *
 * PhpManager:
 *
 * ~~~
 * 'components' => [
 *      'authManager' => [
 *          'class' => 'yii\rbac\PhpManager',
 *      ],
 * ],
 * ~~~
 *
 * DbManager:
 *
 * ~~~
 * 'components' => [
 *      'authManager' => [
 *          'class' => 'yii\rbac\DbManager',
 *      ],
 * ],
 * ~~~
 *
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 * @author Waizabú, yii2 developers in Spain.
 *
 * @since 1.0
 */
class Module extends \yii\base\Module
{


    /**
     * @var string|null a writable directory where custom translations and language elements will be stored.
     */
    public $dataDir = null;

    /**
     * @var string path to file containing the list of languages.
     */
    public $languageListFile = '@eseperio/translatemanager/data/languages.json';
    /**
     * @var string name of the file storing the enabled languages.
     */
    public $enabledLanguagesFilename = 'enabled_languages.json';

    /**
     * @var array the list of IPs that are allowed to access this module.
     */
    public $allowedIPs = ['127.0.0.1', '::1'];

    /**
     * @var array the list of rights that are allowed to access this module.
     * If you modify, you also need to enable authManager.
     * http://www.yiiframework.com/doc-2.0/guide-security-authorization.html
     */
    public $roles = [];

    /**
     * @var string The default export format (yii\web\Response::FORMAT_JSON or yii\web\Response::FORMAT_XML).
     */
    public $defaultExportFormat = Response::FORMAT_JSON;


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->dataDir)) {
            throw new InvalidConfigException("Please configure 'dataDir'!");
        }

        if (!is_writable(Yii::getAlias($this->dataDir))) {
            throw new InvalidConfigException("Directory '{$this->dataDir}' does not exist or is not writable. Please check if directory exists and has write permissions.");
        }

    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->checkAccess()) {
            return parent::beforeAction($action);
        } else {
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        }
    }

    /**
     * @return bool whether the module can be accessed by the current user
     */
    public function checkAccess()
    {
        $ip = Yii::$app->request->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }
        Yii::warning('Access to Translate is denied due to IP address restriction. The requested IP is ' . $ip, __METHOD__);

        return false;
    }


    /**
     * @return false|string the path to the data directory used for stoting files generated by this module
     * @throws \yii\base\Exception
     */
    public function getDataDir()
    {
        $directory = Yii::getAlias($this->dataDir);
        $directory = FileHelper::normalizePath($directory);
        FileHelper::createDirectory($directory);
        return $directory;
    }

    /**
     * @param $iso
     * @return bool
     * @throws \yii\base\Exception
     */
    public function disableLanguage($iso)
    {
        $languages = $this->getEnabledLanguages();
        if (in_array($iso, $languages)) {
            $languages = array_diff($languages, [$iso]);
            $this->setEnabledLanguages($languages);
            return true;
        }
        return false;
    }

    /**
     * Ensures the iso provided is a valid one agains languages array and if so, then
     * adds an entry to the
     * @param $iso
     * @return bool
     * @throws \yii\base\Exception
     */
    public function enableLanguage($iso)
    {
        $languages = $this->getEnabledLanguages();
        if (!in_array($iso, $languages)) {
            $languages[] = $iso;
            $this->setEnabledLanguages($languages);
            return true;
        }
        return false;
    }

    /**
     * @param $iso
     * @return array the language data defined in languages array for given iso
     */
    public function getLanguageData($iso)
    {
        $languages = $this->getAvailableLanguages();
        if (isset($languages[$iso])) {
            return $languages[$iso];
        }

        $iso = StringHelper::truncateMiddle($iso, 10);
        throw new InvalidArgumentException("Language $iso not found");
    }

    /**
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function getEnabledLanguages()
    {
        $filename = $this->getDataDir() . DIRECTORY_SEPARATOR . $this->enabledLanguagesFilename;
        if (file_exists($filename)) {
            $languages = file_get_contents($filename);
            $languages = json_decode($languages, true);
        } else {
            $languages = [];
        }
        return $languages;
    }

    /**
     * @param mixed $languages
     * @throws \yii\base\Exception
     */
    private function setEnabledLanguages(mixed $languages)
    {
        $filename = $this->getDataDir() . DIRECTORY_SEPARATOR . $this->enabledLanguagesFilename;
        file_put_contents($filename, json_encode($languages));
    }

    /**
     * @return array
     */
    public function getAvailableLanguages()
    {
        $filename = Yii::getAlias($this->languageListFile);
        $languages = file_get_contents($filename);
        $languages = json_decode($languages, true);
        $languages = ArrayHelper::index($languages, 'language_id');

        return $languages;
    }

    /**
     * It calculate the progress of translation for a given language by
     * comparing the number of translated items against the total number of items
     * @param $locale
     * @param $category
     * @return array the progress of the translation for given locale and category based on how many items are translated
     * @throws \yii\base\InvalidConfigException
     */
    public function languageProgress($locale, $category): array
    {
        $msgSrcCfg = Yii::$app->i18n->getMessageSource($category);

        // load all the messages defined for default language in configuration
        $defaultLang = $msgSrcCfg->sourceLanguage ?? Yii::$app->sourceLanguage;

        $defaultMessages = $this->loadMessages($category, $msgSrcCfg, $defaultLang);
        $destMessages = $this->loadMessages($category, $msgSrcCfg, $locale);
        $origMsgCount = count($defaultMessages);
        return [
            'translated' => count($destMessages),
            'total' => $origMsgCount,
            'percentage' =>$origMsgCount? round(count($destMessages) / $origMsgCount * 100) : 0
        ];

    }

    /**
     * Load all the messages defined for default language in configuration
     * @param $pattern
     * @param mixed $msgSrcCfg
     * @param string $language
     * @return array<array>
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     */
    public function loadMessages($pattern, MessageSource $msgSrcCfg, string $language)
    {
        return MessageLoaderService::getMessages($pattern, $msgSrcCfg);
    }



}
