<?php

namespace app\assets;

use yii\web\AssetBundle;

class ControlPanelAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/cp.css',
    ];
    public $js = [
        'js/cp.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\FontAwesomeAsset',
    ];
}
