<?php

class m140313_072744_update_images_photo_date extends CDbMigration
{
	public function safeUp()
	{
        $this->update('{{images}}', array('date_photo' => '2014-02-20'));
	}

	public function safeDown()
	{
        $this->update('{{images}}', array('date_photo' => ''));
	}

}