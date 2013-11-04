<?php

class m131104_092447_add_role_column_to_users_table extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('{{users}}', 'role', 'enum("user", "contentManager", "administrator") not null default "user"');
	}

	public function safeDown()
	{
        $this->dropColumn('{{users}}', 'role');
	}

}