<?php

namespace eseperio\translatemanager\controllers\actions;

use eseperio\proshop\common\models\Configuration;
use eseperio\translatemanager\bundles\LanguageAsset;
use eseperio\translatemanager\bundles\LanguagePluginAsset;
use eseperio\translatemanager\models\LanguageTranslateEngine;

use Yii;
use yii\widgets\ActiveForm;
class EngineAction extends \yii\base\Action
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {

        $model = new LanguageTranslateEngine();
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $engine = Yii::$app->request->post('engine', Yii::$app->language);
        $url = Yii::$app->request->post('url', Yii::$app->language);
        $apiKey = Yii::$app->request->post('apiKey', Yii::$app->language);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $privacyPolicyContent = Configuration::get('FILE_TRANSFER_PRIVACY_POLICY');
//            $this->insert($table, [
//                'name' => 'FILE_TRANSFER_PRIVACY_POLICY',
//                'value' => '',
//                'group_id' => 20, // LEGAL_TEXTS
//                'type' => 4, // Text editor
//                'public_name' => 'Política de privacidad para la herramienta de transferencia de archivos',
//                'description' => 'Si no está vacío, se le mostrará al usuario junto con un botón de aceptar.',
//            ]);
//            if ($model->save()) {
//                Yii::$app->session->setFlash('success', 'Configuración guardada con éxito.');
//                return $this->redirect(['index']);
//            } else {
//                Yii::$app->session->setFlash('error', 'Error al guardar la configuración.');
//            }
        }

        return $this->controller->render('engine', ['model' => $model]);
    }

}
