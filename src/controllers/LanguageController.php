<?php

namespace eseperio\translatemanager\controllers;

use eseperio\translatemanager\models\Language;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Controller for managing multilinguality.
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 * @property \eseperio\translatemanager\Module $module
 */
class LanguageController extends Controller
{
    /**
     * @var \eseperio\translatemanager\Module TranslateManager module
     */
    public $module;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'add',
                            'remove',
                            'index',
                            'list',
                            'change-status',
                            'optimizer',
                            'scan',
                            'translate',
                            'save',
                            'dialog',
                            'message',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'delete-source',
                            'import',
                            'export',
                            'auto-translate'
                        ],
                        'roles' => $this->module->roles,
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'eseperio\translatemanager\controllers\actions\IndexAction',
            ],
            'add'=>[
                'class'=> 'eseperio\translatemanager\controllers\actions\AddAction'
            ],
            'remove' => [
                'class' => 'eseperio\translatemanager\controllers\actions\RemoveAction',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'list' => [
                'class' => 'eseperio\translatemanager\controllers\actions\ListAction',
            ],
            'change-status' => [
                'class' => 'eseperio\translatemanager\controllers\actions\ChangeStatusAction',
            ],
            'optimizer' => [
                'class' => 'eseperio\translatemanager\controllers\actions\OptimizerAction',
            ],
            'scan' => [
                'class' => 'eseperio\translatemanager\controllers\actions\ScanAction',
            ],
            'translate' => [
                'class' => 'eseperio\translatemanager\controllers\actions\TranslateAction',
            ],
            'auto-translate' => [
                'class' => 'eseperio\translatemanager\controllers\actions\AutoTranslateAction',
            ],
            'save' => [
                'class' => 'eseperio\translatemanager\controllers\actions\SaveAction',
            ],
            'dialog' => [
                'class' => 'eseperio\translatemanager\controllers\actions\DialogAction',
            ],
            'message' => [
                'class' => 'eseperio\translatemanager\controllers\actions\MessageAction',
            ],
            'view' => [
                'class' => 'eseperio\translatemanager\controllers\actions\ViewAction',
            ],
            'create' => [
                'class' => 'eseperio\translatemanager\controllers\actions\CreateAction',
            ],
            'update' => [
                'class' => 'eseperio\translatemanager\controllers\actions\UpdateAction',
            ],
            'delete' => [
                'class' => 'eseperio\translatemanager\controllers\actions\DeleteAction',
            ],
            'delete-source' => [
                'class' => 'eseperio\translatemanager\controllers\actions\DeleteSourceAction',
            ],
            'import' => [
                'class' => 'eseperio\translatemanager\controllers\actions\ImportAction',
            ],
            'export' => [
                'class' => 'eseperio\translatemanager\controllers\actions\ExportAction',
            ],
        ];
    }

    /**
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Language the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Returns an ArrayDataProvider consisting of language elements.
     *
     * @param array $languageSources
     *
     * @return ArrayDataProvider
     */
    public function createLanguageSourceDataProvider($languageSources)
    {
        $data = [];
        foreach ($languageSources as $category => $messages) {
            foreach ($messages as $message => $boolean) {
                $data[] = [
                    'category' => $category,
                    'message' => $message,
                ];
            }
        }

        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);
    }
}