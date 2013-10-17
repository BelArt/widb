<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/main_default.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
		),
	)
);
