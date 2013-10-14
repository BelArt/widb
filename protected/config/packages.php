<?php
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
);