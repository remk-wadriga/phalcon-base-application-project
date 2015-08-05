<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Dispatcher;
use components\TimeService;
use components\UserService;
use Phalcon\Events\Manager as EventManager;
use listeners\AuthListener;
use listeners\ControllersListener;
use components\AssetManager;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Event manager
 */
$eventsManager = new EventManager();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->setShared('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);
    $view->setVars($config->view->vars->toArray());

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->voltCompilePath,
                'compiledSeparator' => '_',
                'stat' => true,
                'compileAlways' => true,
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter($config->database->toArray());
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
/*$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});*/

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Set the dispatcher
 */
$di->set('dispatcher', function() use ($eventsManager, $di) {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('controllers');

    // Set the event manager for dispatcher
    $dispatcher->setEventsManager($eventsManager);
    // Attach the listener
    $eventsManager->attach('dispatch', new ControllersListener($di));

    return $dispatcher;
});

/**
 * Set the timeService
 */
$di->set('timeService', function () use ($config) {
    return new TimeService($config->timeService->toArray());
});

/**
 * Set the User Service
 */
$di->set('user', function () use ($config, $di, $eventsManager) {
    $user = new UserService($config->user->toArray());
    $user->setSession($di->get('session'));

    // Set the event manager for user service
    $user->setEventsManager($eventsManager);
    // Attach the listener
    $eventsManager->attach('user', new AuthListener());
    return $user;
});

/**
 * Set the Asset manager
 */
$di->set('assetManager', function() use ($config, $di) {
    $assetManager = new AssetManager(array_merge($config->assetManager->toArray(), [
        'manager' => $di->get('assets')
    ]));
    return $assetManager;
});