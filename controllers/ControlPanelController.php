<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\View;

class ControlPanelController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = User::findIdentity(Yii::$app->user->identity->getId());

        $params = [
            'uid' => $user->getId(),
            'auth_key' => $user->getAuthKey(),
        ];

        $webSocketURL = Yii::$app->params['WSServerUrl'] . '/?' . http_build_query($params);

        $this->view->registerJs('
            var WebSocketURL = "' . $webSocketURL . '";
        ', View::POS_HEAD);

        return $this->render('index');
    }

}
