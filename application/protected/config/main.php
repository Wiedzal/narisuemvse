<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
error_reporting(E_ALL);
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR .'..',
    'name' => 'Narisuemvse',

    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'aliases' => array(
        'xupload' => 'ext.xupload',
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
            //'class' => 'application.modules.admin.AdminModule',
            'modules' => array(
                'images',
                'news',
                'shop' => array(
                    'class' => 'application.modules.admin.modules.shop.ShopModule'
                ),
            )
        ),
        'shop' => array(
            'class' => 'application.modules.shop.ShopModule'
        ),
    ),

    // application components
    'components' => array(
    
        'authManager'=>array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
        ), 
        
        'clientScript' => array(
            'packages' => array(
               'jquery' => array(
                    'baseUrl' => '/js/jquery',
                    'js' => array('jquery-1.9.1.min.js'),
                ),
               'jquery.tree' => array(
                    'baseUrl' => 'js/jquery.tree',
                    'js' => array('tree.js'),
                    'css' => array('css/tree.css'),
                    'depends' => array('jquery'),
                ),
                'bootstrap' => array(
                    'baseUrl' => 'js/bootstrap',
                    'js' => array('js/bootstrap.min.js'),
                ),
                'font-awesome' => array(
                    'baseUrl' => 'js/font-awesome',
                    'css' => array('css/font-awesome.min.css'),
                ),
                'jquery.formstyler' => array(
                    'baseUrl' => 'js/jquery.formstyler',
                    'js' => array('jquery.formstyler.js'),
                ),
                
                'vix-gallery' => array(
                    'baseUrl' => 'js/vix-gallery',
                    'js' => array('js/jquery.vix-gallery.js'),
                    'css' => array('css/gallery.css'),
                    'depends' => array('jquery'),
                ),
            )
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
            'class'=>'UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                'shop/<action:cart|order>'=>'shop/default/<action>',
                'shop/<category:[\w_\/-]+>/<id:[\d]+>'=>'shop/default/view',
                'shop/<category:[\w_\/-]+>'=>'shop/default/category',
                'shop'=>'shop/default/index',
                
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
