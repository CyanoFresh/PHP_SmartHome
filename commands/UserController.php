<?php

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionRegister($username, $password, $email)
    {
        $user = new User([
            'scenario' => 'create',
        ]);
        $user->username = $username;
        $user->email = $email;
        $user->generateAuthKey();
        $user->setPassword($password);

        if ($user->save()) {
            echo 'Success' . PHP_EOL;
            return 1;
        }

        echo 'Errors:' . PHP_EOL;
        var_dump($user->errors);
        return 1;
    }

    public function actionChangePassword($id, $newPassword)
    {
        $user = User::findOne($id);

        if (!$user) {
            echo 'User was not found';
        }

        $user->setPassword($newPassword);
        $user->generateAuthKey();

        if ($user->save()) {
            echo 'Password successfully changed' . PHP_EOL;
            
            return 1;
        }

        echo 'Errors:' . PHP_EOL;
        var_dump($user->errors);

        return 0;
    }

    public function actionCheckPassword($id, $password)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        if ($user->validatePassword($password)) {
            echo 'Password is valid' . PHP_EOL;
            return 1;
        }

        echo 'Password is invalid' . PHP_EOL;
        return 0;
    }
}