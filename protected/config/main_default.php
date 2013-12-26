<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'WIDB',
    'language'=>'ru',
    //'sourceLanguage'=>'ru',
    'homeUrl' => array('collections/index'),

	// preloading 'log' component
	'preload'=>array(
        'log',
        'bootstrap'
    ),

    'aliases' => array(
        'xupload' => 'ext.xupload'
    ),

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
        'application.components.helpers.*',
        'application.widgets.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
            'class' => 'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        'db'=>array(
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'tablePrefix'=>'tbl_',
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => false,
			'rules'=>array(
                ''=>'site/index',
                '<controller:\w+>'=>'<controller>/index',
				//'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

        'bootstrap' => array(
            'class' => 'ext.yiibooster.components.Bootstrap',
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
        'clientScript'=>array(
            'packages' => require_once('packages.php')
        ),
        'assetManager' => array(
            'forceCopy' => YII_DEBUG,
        ),
        'authManager' => array(
            'class' => 'PhpAuthManager',
        ),
        'image' => array(
            'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'ImageMagick',
            // ImageMagick setup path
            //'params'=>array('directory'=>'/opt/local/bin'),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => require_once('params.php'),
);