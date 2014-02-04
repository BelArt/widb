<?php

class m140204_111444_alter_objects_codes extends CDbMigration
{

	public function safeUp()
	{
	    $this->update('{{objects}}', array('code' => 'p_123'));
	}

	public function safeDown()
	{
        $this->update('{{objects}}', array('code' => ''));
	}

}