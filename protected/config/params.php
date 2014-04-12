<?php
return array(
    'adminEmail'=>'snigirev.alexey@gmail.com',

    // общая папка для пользовательских файлов, относительно корня приложения (webroot)
    'filesFolder' => 'files',

    // папка для превью, относительно папки для пользовательских файлов
    'previewsFolder' => 'previews',

    // папка для временных файлов, которые надо переместить/обработать, относительно папки для пользовательских файлов
    'tempFilesFolder' => 'tmp',

    // имя состояния для хранения данных подгруженных превью расширения xupload
    'xuploadStatePreviewsName' => '__xuploadPreviews',

    // путь к файлу с данными о версиях превью, внутри protected
    // @see MyPreviewHelper::getPreviewsVersion(), MyPreviewHelper::setPreviewsVersion()
    'previewsVersionsFile' => 'runtime'.DIRECTORY_SEPARATOR.'previews_versions.txt',
);