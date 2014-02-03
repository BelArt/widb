<?php

class m140203_083657_add_period_to_objects extends CDbMigration
{
	public function safeUp()
	{
        $this->update('{{objects}}', array('period' => '1234-5678гг'));
	}

	public function safeDown()
	{
        $this->update('{{objects}}', array('period' => ''));
	}

}