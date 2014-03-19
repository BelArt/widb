<?php

class m140319_085650_add_author_text_field_to_objects extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{objects}}', 'author_text', 'varchar(150) not null default ""');
    }

    public function safeDown()
    {
        $this->dropColumn('{{objects}}', 'author_text');
    }

}