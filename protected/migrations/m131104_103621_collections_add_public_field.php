<?php

class m131104_103621_collections_add_public_field extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('{{collections}}', 'public', 'boolean not null default 1');
	}

	public function safeDown()
	{
        $this->dropColumn('{{collections}}', 'public');
	}

}