<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/console_default.php'),
    array(
        'components'=>array(
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=widb',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'protoportorg',
                'charset' => 'utf8',
            ),
        ),
    )
);