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
    'emptyLayout' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/empty-layout.css'),
        'depends' => array('boosterFix')
    ),
    'loginForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/login-form.js'),
        'depends' => array('boosterFix')
    ),
    'collections' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/collections.css'),
        'depends' => array('boosterFix')
    ),
    'collectionForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/collection-form.js'),
        'css'=>array('css/collection-form.css'),
        'depends' => array('boosterFix')
    ),
);