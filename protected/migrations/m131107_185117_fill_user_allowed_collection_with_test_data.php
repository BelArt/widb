<?php

class m131107_185117_fill_user_allowed_collection_with_test_data extends CDbMigration
{
	public function safeUp()
	{
        $this->insert('{{user_allowed_collection}}', array(
            'collection_id' => '1',
            'user_id' => '1',
            'date_create' => '2013-10-26 13:30:00',
            'date_modify' => 'd2013-10-26 13:30:00',
            'user_create' => '1',
            'user_modify' => '1',
        ));
	}

	public function safeDown()
	{
        $this->truncateTable('{{user_allowed_collection}}');
	}

}