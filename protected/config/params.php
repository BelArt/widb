<?php
return array(
    'adminEmail'=>'snigirev.alexey@gmail.com',

    // папка для превью, относительно папки для пользовательских файлов (files)
    'previewsFolder' => 'previews',

    // папка для временных файлов, которые надо переместить/обработать, относительно папки для пользовательских файлов (files)
    'tempFilesFolder' => 'tmp',

    // имя состояния для хранения данных подгруженных превью расширения xupload
    'xuploadStatePreviewsName' => '__xuploadPreviews',

    // путь к файлу с данными о версиях превью, внутри protected
    // @see MyPreviewHelper::getPreviewsVersion(), MyPreviewHelper::setPreviewsVersion()
    'previewsVersionsFile' => 'runtime'.DIRECTORY_SEPARATOR.'previews_versions.txt',
);