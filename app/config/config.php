<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'phalcon',
        'password'    => 'phalcon',
        'dbname'      => 'phalcon',
        'charset'     => 'utf8',
    ],

    'application' => [
        'migrationsDir'     => APP_PATH . '/app/migrations/',
        'viewsDir'          => APP_PATH . '/app/views/',
        'pluginsDir'        => APP_PATH . '/app/plugins/',
        'libraryDir'        => APP_PATH . '/app/library/',
        'cacheDir'          => APP_PATH . '/app/cache/',
        //Namespaces paths
        'abstractsPath'     => APP_PATH . '/app/abstracts',
        'componentsPath'    => APP_PATH . '/app/components',
        'controllersPath'   => APP_PATH . '/app/controllers',
        'modelsPath'        => APP_PATH . '/app/models',
        'interfacesPath'    => APP_PATH . '/app/interfaces',
        'formsPath'         => APP_PATH . '/app/forms',
        'listenersPath'     => APP_PATH . '/app/listeners',
        'assetsPath'        => APP_PATH . '/app/assets',
        'widgetsPath'       => APP_PATH . '/app/widgets',

        'baseUri'           => '/',
    ],

    'timeService' => [
        'dateFormat' => 'Y-m-d',
        'dateTimeFormat' => 'Y-m-d H:i:s',
    ],

    'user' => [
        'identityClass' => 'models\User',
    ],

    'assetManager' => [
        'collections' => [
            'main' => [
                'priority' => 1,
            ],
        ],
        'basePath' => '/assets',
        'cssPath' => '/css',
        'jsPath' => '/js',
        'fontsPath' => '/fonts',
        'assetsNamespace' => 'assets',
    ],

    'view' => [
        'vars' => [

        ],
    ],

    'volt' => [
        'compilePath' => APP_PATH . '/app/cache/volt/',
        'functions' => [
            'left_menu' => [
                'function' => '\\widgets\\accordion\\AccordionWidget::run',
                'params' => [
                    'modelClass' => '\\models\\LeftMenu',
                    'methodName' => 'getItemsArray',
                ]
            ],
        ]
    ],
]);
