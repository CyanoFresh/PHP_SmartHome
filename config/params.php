<?php

use yii\helpers\ArrayHelper;

$params = [
    'domain' => 'home',
    'WSServerUrl' => 'ws://localhost:8081',
    'arestURL' => 'http://localhost',
];

return ArrayHelper::merge($params, require 'params-local.php');
