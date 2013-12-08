<?php

class m131017_162801_create_object_types_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_object_types', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'name' => 'varchar(150) not null default ""',
            'description' => 'text',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('IX_tbl_object_types_sort', 'tbl_object_types', 'sort', false);
        $this->createIndex('IX_tbl_object_types_deleted', 'tbl_object_types', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_object_types');
    }
}