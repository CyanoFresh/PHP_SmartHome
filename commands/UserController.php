<?php

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\helpers\VarDumper;

class UserController extends Controller
{
    public function actionRegister($username, $password, $email)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->generateAuthKey();
        $user->setPassword($password);
        
        if ($user->save()) {
            return 'Success' . PHP_EOL;
        }
        
        return var_dump($user->errors);
    }
}