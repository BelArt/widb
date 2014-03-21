<?php
/*
 * Сначала подключается defaultLayout, потом стили всех виджетов booster'а, потом layoutFix, потом global.
 * Для остальных пакетов надо указывать зависимость от global'а,чтобы они подключались после него.
 * В layoutFix - правки стилей виджетов booster'а и defaultLayout.
 * В global - стили общих элементов проекта.
 * Для каждого раздела, если там свои стили, которые нигде больше не используются, можно создать свой пакет,
 * который подключить во view этого раздела.
 */
return array(
    'defaultLayout' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/screen.css', 'css/main.css'),
    ),
    'layoutFix' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/layout-fix.css'),
        'depends' => array('bootstrap.css', 'bootstrap.js')
    ),
    'global' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/global.css', 'plugins/fancybox2/jquery.fancybox.css'),
        'js'=>array('js/global.js', 'plugins/fancybox2/jquery.fancybox.pack.js', 'plugins/jsuri/Uri.js'),
        'depends' => array('layoutFix')
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
    'objectForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/object-form.js', 'plugins/litranslit/jquery.liTranslit.js'),
        'css'=>array('css/object-form.css'),
        'depends' => array('global')
    ),
    'collectionView' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/collection-view.css'),
        'js'=>array('js/collection-view.js'),
        'depends' => array('global')
    ),
    'uploadFiles' => array(
        'basePath'=>'application.assets',
        'css'=>array('css/upload-files.css'),
        'js'=>array('js/upload-files.js'),
        'depends' => array('global')
    ),
    'objectView' => array(
        'basePath'=>'application.assets',
        //'css'=>array('css/object-view.css'),
        'js'=>array('js/object-view.js'),
        'depends' => array('global')
    ),
    'imageView' => array(
        'basePath'=>'application.assets',
        //'css'=>array('css/object-view.css'),
        'js'=>array('js/image-view.js'),
        'depends' => array('global')
    ),
    'imageForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/image-form.js'),
        'css'=>array('css/image-form.css'),
        'depends' => array('global')
    ),
    'dictionariesView' => array(
        'basePath'=>'application.assets',
        //'css'=>array('css/object-view.css'),
        'js'=>array('js/dictionary-view.js'),
        'depends' => array('global')
    ),
    'dictionaryForm' => array(
        'basePath'=>'application.assets',
        'js'=>array('js/dictionary-form.js'),
        'css'=>array('css/dictionary-form.css'),
        'depends' => array('global')
    ),
);