<?php

use yii\helpers\ArrayHelper;

$params = [
    'domain' => 'home',
    'WSServerUrl' => 'ws://localhost:8081',
    'arestURL' => 'http://176.36.54.229',
];

return ArrayHelper::merge($params, require 'params-local.php');