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
        'voltCompilePath'   => APP_PATH . '/app/cache/volt/',
        //Namespaces paths
        'abstractsPath'     => APP_PATH . '/app/abstracts',
        'componentsPath'    => APP_PATH . '/app/components',
        'controllersPath'   => APP_PATH . '/app/controllers',
        'modelsPath'        => APP_PATH . '/app/models',
        'interfacesPath'    => APP_PATH . '/app/interfaces',

        'baseUri'           => '/',
    ],

    'timeService' => [
        'dateFormat' => 'Y-m-d',
        'dateTimeFormat' => 'Y-m-d H:i:s',
    ],

    'user' => [
        'identityClass' => 'models\User',
    ],
]);
