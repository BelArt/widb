<?php

class m131109_094132_drop_column_image_from_collections extends CDbMigration
{
	public function safeUp()
	{
        $this->dropColumn('{{collections}}', 'image');
	}

	public function safeDown()
	{
        $this->addColumn('{{collections}}', 'image', 'varchar(150) not null default ""');
	}

}