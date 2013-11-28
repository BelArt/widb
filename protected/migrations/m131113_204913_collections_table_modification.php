<?php

class m131113_204913_collections_table_modification extends CDbMigration
{
	public function safeUp()
	{
        $this->dropColumn('{{collections}}', 'public');
        $this->addColumn('{{collections}}', 'temporary_public', 'boolean not null default 0');
	}

	public function safeDown()
	{
        $this->dropColumn('{{collections}}', 'temporary_public');
        $this->addColumn('{{collections}}', 'public', 'boolean not null default 1');
	}

}