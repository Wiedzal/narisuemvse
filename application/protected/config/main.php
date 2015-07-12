<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
error_reporting(E_ALL);
return array(
    'basePath' => dirname(__FILE__). DIRECTORY_SEPARATOR .'..',
    'name' => 'Narisuemvse',

    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1','::1'),
        ),
        'admin' => array(
            'modules' => array(
                'images',
                'news',
            )
        ),
    ),

    // application components
    'components' => array(
    
        'authManager'=>array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
        ), 

        'simpleImage'=>array(
            'class' => 'application.extensions.SimpleImage',
        ),
        
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'returnUrl' => array('site/about'),
            //'homeUrl' => array('site/index'),
        ),
        
        // uncomment the following to enable URLs in path-format
        
        'urlManager' => array(
            'showScriptName' => false,
            'urlFormat' => 'path',
            'rules' => array(
                '/' => 'site/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        
        // database settings are configured in database.php
        'db' => require(dirname(__FILE__).'/database.php'),
        
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        
        'log'=>array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),

    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminUsername' => 'admin',
        'adminEmail' => 'alkos10ko@mail.com',
        'adminTheme' => 'admin',
    ),
    
    'charset' => 'utf-8',
    'language' => 'ru',
    'theme' => 'narisuemvse',
);
