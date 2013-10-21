<?php
/*
 * Сначала подключается defaultLayout, потом все пакеты booster'a,
 * Для остальных пакетов надо указывать зависимость 'depends' => array('bootstrap.css', 'bootstrap.js'),
 * чтобы они подключались после всех пакетов booster'a
 */
return array(
    'defaultLayout' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/screen.css', 'css/main.css'),
    ),
    'emptyLayout' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/empty-layout.css'),
        'depends' => array('bootstrap.css', 'bootstrap.js') // подключится после booster'а
    ),
    'loginForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/login-form.js'),
        'depends' => array('bootstrap.css', 'bootstrap.js') // подключится после booster'а
    ),
    'collections' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/collections.css'),
        'depends' => array('bootstrap.css', 'bootstrap.js') // подключится после booster'а
    ),
);