<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class FontAwesomeAsset
 *
 * Import only needed icons
 * 
 * @package app\assets
 */
class FontAwesomeAsset extends AssetBundle
{
    public $js = [
        'https://use.fonticons.com/523fbe74.js',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
}