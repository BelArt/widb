<?php

class m131104_093311_set_admin_roles extends CDbMigration
{
	public function safeUp()
	{
        $this->update('{{users}}', array('role' => 'administrator'));
	}

	public function safeDown()
	{
        $this->update('{{users}}', array('role' => 'user'));
	}

}