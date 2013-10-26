<?php

class m131026_091859_alter_users_table extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('{{users}}', 'email', 'varchar(150) not null default ""');
        $this->addColumn('{{users}}', 'password', 'char(64) not null default ""');
	}

	public function safeDown()
	{
        $this->dropColumn('{{users}}', 'email');
        $this->dropColumn('{{users}}', 'password');
	}

}