<?php

namespace app\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700',
        'css/bootstrap.css',
        'css/login.css',
    ];
    public $js = [
        'js/login.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
