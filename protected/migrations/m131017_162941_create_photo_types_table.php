<?php

class m131017_162941_create_photo_types_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_photo_types', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'name' => 'varchar(150) not null default ""',
            'description' => 'text',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ));

        $this->createIndex('IX_tbl_photo_types_sort', 'tbl_photo_types', 'sort', false);
        $this->createIndex('IX_tbl_photo_types_deleted', 'tbl_photo_types', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_photo_types');
    }
}