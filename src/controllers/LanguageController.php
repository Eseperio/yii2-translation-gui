<?php

namespace eseperio\translatemanager\src\controllers;

use eseperio\translatemanager\src\models\Language;
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
    public $defaultAction = 'list';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['list', 'change-status', 'optimizer', 'scan', 'translate', 'save', 'dialog', 'message', 'view', 'create', 'update', 'delete', 'delete-source', 'import', 'export', 'auto-translate'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['list', 'change-status', 'optimizer', 'scan', 'translate', 'save', 'dialog', 'message', 'view', 'create', 'update', 'delete', 'delete-source', 'import', 'export', 'auto-translate'],
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'list' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\ListAction',
            ],
            'change-status' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\ChangeStatusAction',
            ],
            'optimizer' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\OptimizerAction',
            ],
            'scan' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\ScanAction',
            ],
            'translate' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\TranslateAction',
            ],
            'auto-translate' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\AutoTranslateAction',
            ],
            'save' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\SaveAction',
            ],
            'dialog' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\DialogAction',
            ],
            'message' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\MessageAction',
            ],
            'view' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\ViewAction',
            ],
            'create' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\CreateAction',
            ],
            'update' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\UpdateAction',
            ],
            'delete' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\DeleteAction',
            ],
            'delete-source' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\DeleteSourceAction',
            ],
            'import' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\ImportAction',
            ],
            'export' => [
                'class' => 'eseperio\translatemanager\src\controllers\actions\ExportAction',
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
