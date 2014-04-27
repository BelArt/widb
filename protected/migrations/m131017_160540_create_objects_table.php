<?php

class m131017_160540_create_objects_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('tbl_objects', array(
            'id' => 'int unsigned not null auto_increment primary key',
            'author_id' => 'int unsigned not null default 0',
            'type_id' => 'int unsigned not null default 0',
            'collection_id' => 'int unsigned not null default 0',
            'name' => 'varchar(150) not null default ""',
            'description' => 'text',
            'inventory_number' => 'varchar(50) not null default ""',
            'inventory_number_en' => 'varchar(50) not null default ""',
            'code' => 'varchar(150) not null default ""',
            'width' => 'decimal(5,2) unsigned not null default 0',
            'height' => 'decimal(5,2) unsigned not null default 0',
            'depth' => 'decimal(5,2) unsigned not null default 0',
            'has_preview' => 'boolean not null default 0',
            'department' => 'varchar(150) not null default ""',
            'keeper' => 'varchar(150) not null default ""',
            'date_create' => 'datetime not null default 0',
            'date_modify' => 'datetime not null default 0',
            'date_delete' => 'datetime not null default 0',
            'sort' => 'int unsigned not null default 0',
            'deleted' => 'boolean not null default 0',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('IX_objects_author_id', 'tbl_objects', 'author_id', false);
        $this->createIndex('IX_objects_type_id', 'tbl_objects', 'type_id', false);
        $this->createIndex('IX_objects_collection_id', 'tbl_objects', 'collection_id', false);
        $this->createIndex('IX_objects_sort', 'tbl_objects', 'sort', false);
        $this->createIndex('IX_objects_deleted', 'tbl_objects', 'deleted', false);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_objects');
    }
}