<?php

class m140321_084737_add_size_columns_to_images_table extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('{{images}}', 'width_cm', 'decimal(5,2) unsigned NOT NULL DEFAULT 0');
        $this->addColumn('{{images}}', 'height_cm', 'decimal(5,2) unsigned NOT NULL DEFAULT 0');
	}

	public function safeDown()
	{
        $this->dropColumn('{{images}}', 'width_cm');
        $this->dropColumn('{{images}}', 'height_cm');
	}

}