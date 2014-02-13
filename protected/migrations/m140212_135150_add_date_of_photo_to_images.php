<?php

class m140212_135150_add_date_of_photo_to_images extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{images}}', 'date_photo', 'date not null default 0');
    }

    public function safeDown()
    {
        $this->dropColumn('{{images}}', 'date_photo');
    }

}