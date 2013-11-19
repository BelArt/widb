<?php
/*
 * Сначала подключается defaultLayout, потом все пакеты booster'а, потом boosterFix
 * Для остальных пакетов надо указывать зависимость от boosterFix'а,чтобы они подключались после него
 */
return array(
    'defaultLayout' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/screen.css', 'css/main.css'),
    ),
    'boosterFix' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/booster-fix.css'),
        'depends' => array('bootstrap.css', 'bootstrap.js') // подключится после всех виджетов booster'а
    ),
    'global' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/global.css'),
        'depends' => array('boosterFix')
    ),
    'emptyLayout' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/empty-layout.css'),
        'depends' => array('global')
    ),
    'loginForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/login-form.js'),
        'depends' => array('global')
    ),
    'collections' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/collections.css'),
        'depends' => array('global')
    ),
    'collectionForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/collection-form.js'),
        'css'=>array('css/collection-form.css'),
        'depends' => array('global')
    ),
    'collectionView' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/collection-view.css'),
        'depends' => array('global')
    ),
);