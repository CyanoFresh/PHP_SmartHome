<?php

return [
    'bootstrap' => ['debug', 'gii'],
    'components' => [
        'mailer' => [
            'useFileTransport' => true,
        ],
        'request' => [
            'cookieValidationKey' => '',
        ],
        'view' => [
            'class' => '\rmrevin\yii\minify\View',
            'minify_path' => '@webroot/assets',
            'js_position' => [\yii\web\View::POS_END],
            'force_charset' => 'UTF-8',
        ],
    ],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'crud' => [
                    'class' => 'yii\gii\generators\crud\Generator',
                    'templates' => [
                        'myCrud' => '@app/giiTemplates/crud/my',
                    ]
                ]
            ],
        ],
    ],
];